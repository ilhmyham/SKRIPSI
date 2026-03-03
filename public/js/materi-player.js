/**
 * materi-player.js
 * Handles YouTube / Google Drive video playback inside the Learning Modal.
 * Telah dioptimalkan dengan PRE-LOADING (Background Initialization) agar memuat instan.
 */

// ─── YouTube IFrame API Bootstrap ────────────────────────────────────────────
var tag = document.createElement('script');
tag.src = "https://www.youtube.com/iframe_api";
document.head.appendChild(tag);

// ─── State ────────────────────────────────────────────────────────────────────
var ytPlayer = null;
var startTime = 0;
var endTime = 0;
var loopInterval;
var ytReady = false;

// Pre-initialize player di background saat halaman dimuat
function onYouTubeIframeAPIReady() {
    ytReady = true;
    _initYtPlayer();
}

function _initYtPlayer() {
    if (ytPlayer) return;

    const wrapper = getVideoWrapper();
    if (!wrapper) return;

    // Pastikan kontainer player ada
    if (!document.getElementById('player')) {
        wrapper.insertAdjacentHTML('beforeend', `
            <div id="player" style="display:none; width:100%; height:100%;"></div>
            <!-- Click Shield untuk memblokir klik pada bar Title & Share di bagian atas -->
            <div id="yt-shield" class="absolute top-0 left-0 right-0 h-16 z-[5] hidden pointer-events-auto cursor-default"></div>
        `);
    }

    ytPlayer = new YT.Player('player', {
        height: '100%', width: '100%',
        playerVars: {
            autoplay: 1,
            controls: 1,
            rel: 0,
            modestbranding: 1,
            playsinline: 1,
            iv_load_policy: 3, // Menyembunyikan kartu info
            fs: 0,             // Menonaktifkan fullscreen
            disablekb: 1
        },
        events: {
            onReady: function (event) {
                // Player sudah siap di memori
            },
            onStateChange: _onPlayerStateChange
        }
    });
}

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

function openLearningModal(data) {
    const { id, huruf, judul, video = '', desc = '', gambar = '', completeUrl } = data;

    const startMatch = video.match(/[?&](?:start|t)=([\d]+)/);
    const endMatch = video.match(/[?&]end=([\d]+)/);
    startTime = startMatch ? parseInt(startMatch[1]) : 0;
    endTime = endMatch ? parseInt(endMatch[1]) : 0;

    document.getElementById('modalHurufTitle').innerText = huruf ? `( ${huruf} )` : '';
    document.getElementById('modalDeskripsi').innerText = desc || 'Perhatikan gerakan tangan.';
    document.getElementById('formSelesai').action = completeUrl;

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

    document.getElementById('learningModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    _loadVideo(video);
}

function _showOverlaySpinner(pesan = 'Memuat video...') {
    const wrapper = getVideoWrapper();
    // Hapus pesan lama jika ada
    const existing = document.getElementById('videoSpinner');
    if (existing) existing.remove();

    wrapper.insertAdjacentHTML('beforeend', `
        <div id="videoSpinner" class="flex flex-col h-full w-full items-center justify-center bg-gray-900 absolute inset-0 z-10 rounded-xl gap-3">
            <div class="relative w-14 h-14">
                <div class="absolute inset-0 rounded-full border-4 border-gray-700"></div>
                <div class="absolute inset-0 rounded-full border-4 border-t-emerald-400 animate-spin"></div>
            </div>
            <p class="text-gray-400 text-sm font-medium" id="spinnerText">${pesan}</p>
        </div>
    `);
}

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

    // Clear Drive iframe first if it exists
    const oldDrive = wrapper.querySelector('iframe.drive-player');
    if (oldDrive) oldDrive.remove();

    // Clear unsupported messages
    const oldMsg = wrapper.querySelector('.unsupported-msg');
    if (oldMsg) oldMsg.remove();

    if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
        const videoId = extractVideoId(videoUrl);
        const playerEl = document.getElementById('player');

        // Show YouTube iframe, hide spinner when ready
        const shieldEl = document.getElementById('yt-shield');
        if (playerEl) playerEl.style.display = 'block';
        if (shieldEl) shieldEl.classList.remove('hidden');

        if (ytPlayer && typeof ytPlayer.loadVideoById === 'function') {
            _showOverlaySpinner('Menyiapkan pemutar...');
            ytPlayer.loadVideoById({
                videoId: videoId,
                startSeconds: startTime,
                endSeconds: endTime
            });
            _startLoopCheck();
        } else {
            // Jika API telat, fallback buat instance
            _showOverlaySpinner('Menghubungkan ke server...');
            _initYtPlayer();
            setTimeout(() => _loadVideo(videoUrl), 500); // retry
        }

    } else if (videoUrl.includes('drive.google.com')) {
        const driveId = extractDriveId(videoUrl);

        // Sembunyikan YT player (jangan dihancurkan)
        const playerEl = document.getElementById('player');
        const shieldEl = document.getElementById('yt-shield');
        if (playerEl) playerEl.style.display = 'none';
        if (shieldEl) shieldEl.classList.add('hidden');
        if (ytPlayer && typeof ytPlayer.pauseVideo === 'function') ytPlayer.pauseVideo();
        if (loopInterval) clearInterval(loopInterval);

        if (driveId) {
            _showOverlaySpinner('Menyinkronkan Google Drive...');
            const iframe = document.createElement('iframe');
            iframe.src = `https://drive.google.com/file/d/${driveId}/preview`;
            iframe.width = '100%'; iframe.height = '100%';
            iframe.className = 'drive-player';
            iframe.style.cssText = 'border:none;border-radius:0.75rem;';
            iframe.onload = () => {
                const spinner = document.getElementById('videoSpinner');
                if (spinner) spinner.remove();
            };
            wrapper.appendChild(iframe);
        }

    } else if (videoUrl) {
        // Sembunyikan player
        const playerEl = document.getElementById('player');
        const shieldEl = document.getElementById('yt-shield');
        if (playerEl) playerEl.style.display = 'none';
        if (shieldEl) shieldEl.classList.add('hidden');

        wrapper.insertAdjacentHTML('beforeend', '<div class="unsupported-msg absolute inset-0 flex h-full items-center justify-center text-gray-400 bg-gray-900 rounded-xl text-sm z-20">Format URL tidak didukung.</div>');
    } else {
        const playerEl = document.getElementById('player');
        const shieldEl = document.getElementById('yt-shield');
        if (playerEl) playerEl.style.display = 'none';
        if (shieldEl) shieldEl.classList.add('hidden');

        wrapper.insertAdjacentHTML('beforeend', `
            <div class="unsupported-msg absolute inset-0 flex flex-col h-full items-center justify-center bg-gray-900 rounded-xl gap-3 px-6 text-center z-20">
                <div class="w-16 h-16 rounded-full bg-gray-700 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6" class="text-red-400"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-base">Video Belum Tersedia</p>
                    <p class="text-gray-400 text-xs mt-1">Materi ini belum memiliki video.</p>
                </div>
            </div>`);
    }
}

function closeModal() {
    document.getElementById('learningModal').classList.add('hidden');
    document.body.style.overflow = '';

    // Pause player instead of destroying
    if (ytPlayer && typeof ytPlayer.pauseVideo === 'function') {
        ytPlayer.pauseVideo();
    }
    if (loopInterval) clearInterval(loopInterval);

    // Stop Drive audio
    const wrapper = getVideoWrapper();
    if (wrapper) {
        const driveIframe = wrapper.querySelector('iframe.drive-player');
        if (driveIframe) driveIframe.remove();
    }
}

// ─── YouTube callbacks ────────────────────────────────────────────────────────
function _onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.PLAYING) {
        const spinner = document.getElementById('videoSpinner');
        if (spinner) spinner.remove();
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

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !document.getElementById('learningModal').classList.contains('hidden')) {
        closeModal();
    }
});