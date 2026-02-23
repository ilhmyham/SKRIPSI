/**
 * materi-player.js
 * Handles YouTube / Google Drive video playback inside the Learning Modal.
 * Loaded on siswa/materi/index only.
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

    // Load video
    _loadVideo(video);
}

/**
 * Open modal from a data-* bearing DOM element (blade loop cards).
 * Reads attributes and delegates to openLearningModal.
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

    // Always destroy the previous player instance first.
    // Calling loadVideoById on a detached player causes "not attached to DOM" errors.
    _destroyYtPlayer();

    if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
        const videoId = extractVideoId(videoUrl);
        // Reset wrapper to a fresh target div
        wrapper.innerHTML = '<div id="player"></div>';

        ytPlayer = new YT.Player('player', {
            height: '100%', width: '100%', videoId,
            playerVars: { autoplay: 1, controls: 1, rel: 0, modestbranding: 1, start: startTime, end: endTime },
            events: { onReady: _onPlayerReady, onStateChange: _onPlayerStateChange }
        });

        _startLoopCheck();

    } else if (videoUrl.includes('drive.google.com')) {
        const driveId = extractDriveId(videoUrl);
        wrapper.innerHTML = driveId
            ? `<iframe src="https://drive.google.com/file/d/${driveId}/preview" width="100%" height="100%" allow="autoplay" style="border:none;border-radius:0.75rem;"></iframe>`
            : '<div class="flex h-full items-center justify-center text-white bg-gray-800 rounded-xl">URL Drive tidak valid</div>';

    } else if (videoUrl) {
        wrapper.innerHTML = '<div class="flex h-full items-center justify-center text-gray-400 bg-gray-900 rounded-xl text-sm">Format URL tidak didukung. Gunakan YouTube atau Google Drive.</div>';
    } else {
        wrapper.innerHTML = '<div class="flex h-full items-center justify-center text-gray-400 bg-gray-900 rounded-xl text-sm">Tidak ada video untuk materi ini.</div>';
    }
}


function closeModal() {
    document.getElementById('learningModal').classList.add('hidden');
    document.body.style.overflow = '';

    if (ytPlayer && typeof ytPlayer.stopVideo === 'function') ytPlayer.stopVideo();
    if (loopInterval) clearInterval(loopInterval);

    // Stop Drive audio by replacing iframe
    const wrapper = getVideoWrapper();
    if (wrapper && wrapper.querySelector('iframe[src*="drive.google.com"]')) {
        wrapper.innerHTML = '<div id="player"></div>';
        ytPlayer = null;
    }
}

// ─── YouTube callbacks ────────────────────────────────────────────────────────
function _onPlayerReady(event) {
    event.target.seekTo(startTime);
    event.target.playVideo();
}

function _onPlayerStateChange(event) {
    if (event.data === YT.PlayerState.ENDED) {
        event.target.seekTo(startTime);
        event.target.playVideo();
    }
}

function _startLoopCheck() {
    if (loopInterval) clearInterval(loopInterval);
    loopInterval = setInterval(function () {
        if (ytPlayer && typeof ytPlayer.getCurrentTime === 'function' && endTime > 0) {
            if (ytPlayer.getCurrentTime() >= endTime) ytPlayer.seekTo(startTime);
        }
    }, 500);
}

function _destroyYtPlayer() {
    if (loopInterval) clearInterval(loopInterval);
    if (ytPlayer && typeof ytPlayer.destroy === 'function') { ytPlayer.destroy(); ytPlayer = null; }
}

// ─── Keyboard shortcut ───────────────────────────────────────────────────────
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && !document.getElementById('learningModal').classList.contains('hidden')) {
        closeModal();
    }
});
