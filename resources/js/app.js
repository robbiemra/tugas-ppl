import './bootstrap';

const toggleBackgroundMusic = (event) => {
    const btn = document.getElementById('musicToggleBtn');
    const bgm = document.getElementById('backgroundMusic');
    if (!btn || !bgm) return;

    const clicked = event.target.closest('#musicToggleBtn');
    if (!clicked) return;

    event.preventDefault();
    if (bgm.paused) {
        bgm.play().then(() => {
            btn.textContent = '🔊';
        }).catch(() => {
            // Autoplay may be blocked, but user click should allow play in most browsers.
        });
    } else {
        bgm.pause();
        btn.textContent = '🔇';
    }
};

window.playBackgroundMusicGesture = () => {
    const btn = document.getElementById('musicToggleBtn');
    const bgm = document.getElementById('backgroundMusic');
    if (!btn || !bgm) return;

    if (bgm.paused) {
        bgm.play().then(() => {
            btn.textContent = '🔊';
        }).catch(() => {
            // Could not play automatically, user must click the speaker button.
        });
    }
};

document.addEventListener('click', toggleBackgroundMusic);
