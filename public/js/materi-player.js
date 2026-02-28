/**
 * materi-player.js
 * Handles YouTube / Google Drive video playback inside the Learning Modal.
 * Telah dioptimalkan untuk menggunakan ulang instance (Reuse Player) agar memuat lebih cepat.
 */

// ─── YouTube IFrame API Bootstrap ────────────────────────────────────────────
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
document.head.appendChild(tag);

// ─── State ────────────────────────────────────────────────────────────────────
var ytPlayer = null;   // YouTube IFrame Player instance
var startTime = 0;
var endTime = 0;
var loopInterval;

// Called automatically by YT API when ready
function onYouTubeIframeAPIReady() { /* ready */ }

// ─── Helpers ──────────────────────────────────────────────────────────────────
function extractVideoId(url) {
    if (!url) return null;
    const m = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
    return m ? m[1] : null;
}

function extractDriveId(url) {
    if (!url) return null;
    const m = url.match(/(?:drive\.google\.com\/file\/d\/|drive\.google\.com\/open\?id=)([^/&?]+)/);
    return m ? m[1] : null;
}

function getVideoWrapper() {
    return document.getElementById('videoWrapper');
}

// ─── Modal ────────────────────────────────────────────────────────────────────

/**
 * Open the learning modal with data from a plain object.
 * @param {{ id, huruf, judul, video, desc, gambar, completeUrl }} data
 */
function openLearningModal(data) {
    const { id, huruf, judul, video = '', desc = '', gambar = '', completeUrl } = data;

    // Parse start / end timestamps from URL query params
    const startMatch = video.match(/[?&](?:start|t)=([\d]+)/);
    const endMatch = video.match(/[?&]end=([\d]+)/);
    startTime = startMatch ? parseInt(startMatch[1]) : 0;
    endTime = endMatch ? parseInt(endMatch[1]) : 0;

    // Update modal UI
    document.getElementById('modalHurufTitle').innerText = huruf ? `( ${huruf} )` : '';
    document.getElementById('modalDeskripsi').innerText = desc || 'Perhatikan gerakan tangan.';
    document.getElementById('formSelesai').action = completeUrl;

    // Gambar panduan
    const imgEl = document.getElementById('staticImage');
    const noImgEl = document.getElementById('noImageText');
    if (gambar) {
        imgEl.src = gambar;
        imgEl.classList.remove('hidden');
        noImgEl.classList.add('hidden');
    } else {
        imgEl.classList.add('hidden');
        noImgEl.classList.remove('hidden');
    }

    // Show modal
    document.getElementById('learningModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Muat video
    _loadVideo(video);
}

/** * Tampilkan overlay spinner saat player di-reuse
 */
function _showOverlaySpinner() {
    const wrapper = getVideoWrapper();
    if (!document.getElementById('videoSpinner')) {
        wrapper.insertAdjacentHTML('beforeend', `
            <div id="videoSpinner" class="flex flex-col h-full w-full items-center justify-center bg-gray-900 absolute inset-0 z-10 rounded-xl">
                <div class="relative w-14 h-14">
                    <div class="absolute inset-0 rounded-full border-4 border-gray-700"></div>
                    <div class="absolute inset-0 rounded-full border-4 border-t-emerald-400 animate-spin"></div>
                </div>
            </div>
        `);
    }
}

/**
 * Open modal from a data-* bearing DOM element (blade loop cards).
 */
function openModalFromElement(el) {
    openLearningModal({
        id: el.dataset.id,
        huruf: el.dataset.huruf,
        judul: el.dataset.judul,
        video: el.dataset.video || '',
        desc: el.dataset.desc || '',
        gambar: el.dataset.gambar || '',
        completeUrl: el.dataset.completeUrl,
    });
}

function _loadVideo(videoUrl) {
    const wrapper = getVideoWrapper();

    if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
        const videoId = extractVideoId(videoUrl);
        const existingPlayerEl = document.getElementById('player');

        // OPTIMASI: Jika player sudah ada, cukup ganti ID videonya
        if (ytPlayer && existingPlayerEl && existingPlayerEl.tagName.toLowerCase() === 'iframe') {
            _showOverlaySpinner();

            ytPlayer.loadVideoById({
                videoId: videoId,
                startSeconds: startTime,
                endSeconds: endTime
            });
            _startLoopCheck();

        } else {
            // BUAT BARU: Hanya berjalan pada klik pertama kali
            _destroyYtPlayer();
            wrapper.innerHTML = `
                <div id="videoSpinner" class="flex flex-col h-full items-center justify-center bg-gray-900 absolute inset-0 z-10 rounded-xl gap-3">
                    <div class="relative w-14 h-14">
                        <div class="absolute inset-0 rounded-full border-4 border-gray-700"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-t-emerald-400 animate-spin"></div>
                    </div>
                    <p class="text-gray-400 text-sm font-medium">Memuat video...</p>
                </div>
                <div id="player" style="display:none; width: 100%; height: 100%;"></div>
            `;

            ytPlayer = new YT.Player('player', {
                height: '100%', width: '100%', videoId,
                playerVars: { autoplay: 1, controls: 1, rel: 0, modestbranding: 1, start: startTime, end: endTime },
                events: { onReady: _onPlayerReady, onStateChange: _onPlayerStateChange }
            });

            _startLoopCheck();
        }

    } else if (videoUrl.includes('drive.google.com')) {
        // Jika beralih ke Drive, hancurkan player YT agar tidak bentrok
        _destroyYtPlayer();
        const driveId = extractDriveId(videoUrl);

        if (driveId) {
            wrapper.innerHTML = `
                <div id="videoSpinner" class="flex flex-col h-full items-center justify-center bg-gray-900 rounded-xl absolute inset-0 z-10">
                    <div class="relative w-14 h-14">
                        <div class="absolute inset-0 rounded-full border-4 border-gray-700"></div>
                        <div class="absolute inset-0 rounded-full border-4 border-t-emerald-400 animate-spin"></div>
                    </div>
                </div>
            `;

            const iframe = document.createElement('iframe');
            iframe.src = `https://drive.google.com/file/d/${driveId}/preview`;
            iframe.width = '100%'; iframe.height = '100%';
            iframe.allow = 'autoplay';
            iframe.style.cssText = 'border:none;border-radius:0.75rem;display:none;';
            iframe.onload = () => {
                const spinner = document.getElementById('videoSpinner');
                if (spinner) spinner.remove();
                iframe.style.display = 'block';
            };
            wrapper.appendChild(iframe);
        } else {
            wrapper.innerHTML = '<div class="flex h-full items-center justify-center text-white bg-gray-800 rounded-xl">URL Drive tidak valid</div>';
        }

    } else if (videoUrl) {
        _destroyYtPlayer();
        wrapper.innerHTML = '<div class="flex h-full items-center justify-center text-gray-400 bg-gray-900 rounded-xl text-sm">Format URL tidak didukung. Gunakan YouTube atau Google Drive.</div>';
    } else {
        _destroyYtPlayer();
        wrapper.innerHTML = `
            <div class="flex flex-col h-full items-center justify-center bg-gray-900 rounded-xl gap-3 px-6 text-center">
                <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6" class="text-red-400"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-base">Video Belum Tersedia</p>
                    <p class="text-gray-400 text-xs mt-1">Materi ini belum memiliki video. Silakan pelajari dari gambar dan deskripsi.</p>
                </div>
            </div>`;
    }
}

function closeModal() {
    document.getElementById('learningModal').classList.add('hidden');
    document.body.style.overflow = '';

    // CUKUP HENTIKAN VIDEO, jangan di-destroy agar bisa di-reuse
    if (ytPlayer && typeof ytPlayer.stopVideo === 'function') {
        ytPlayer.stopVideo();
    }
    if (loopInterval) clearInterval(loopInterval);

    // Stop Drive audio dengan menghapus iframe-nya
    const wrapper = getVideoWrapper();
    if (wrapper && wrapper.querySelector('iframe[src*="drive.google.com"]')) {
        wrapper.innerHTML = '';
    }
}

// ─── YouTube callbacks ────────────────────────────────────────────────────────
function _onPlayerReady(event) {
    const playerEl = document.getElementById('player');
    if (playerEl) playerEl.style.display = '';

    event.target.seekTo(startTime);
    event.target.playVideo();
}

function _onPlayerStateChange(event) {
    // Sembunyikan spinner HANYA ketika video benar-benar mulai diputar
    if (event.data === YT.PlayerState.PLAYING) {
        const spinner = document.getElementById('videoSpinner');
        if (spinner) spinner.remove();

        const playerEl = document.getElementById('player');
        if (playerEl) playerEl.style.display = '';
    }

    if (event.data === YT.PlayerState.ENDED) {
        event.target.seekTo(startTime);
        event.target.playVideo();
    }
}

function _startLoopCheck() {
    if (loopInterval) clearInterval(loopInterval);
    loopInterval = setInterval(function () {
        if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function' && endTime > 0) {
            if (ytPlayer.getCurrentTime() >= endTime) {
                ytPlayer.seekTo(startTime);
            }
        }
    }, 500);
}

function _destroyYtPlayer() {
    if (loopInterval) clearInterval(loopInterval);
    if (ytPlayer && typeof ytPlayer.destroy === 'function') {
        ytPlayer.destroy();
        ytPlayer = null;
    }
}

// ─── Keyboard shortcut ───────────────────────────────────────────────────────
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !document.getElementById('learningModal').classList.contains('hidden')) {
        closeModal();
    }
});