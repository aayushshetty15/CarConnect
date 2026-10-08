// Smooth anchor scrolling
document.addEventListener("click", (e) => {
  const a = e.target.closest('a[href^="#"]');
  if (!a) return;
  const href = a.getAttribute("href");
  if (href.length <= 1) return;
  const el = document.querySelector(href);
  if (el) {
    e.preventDefault();
    el.scrollIntoView({ behavior: "smooth", block: "start" });
  }
});

// Mobile navigation toggle
document.addEventListener("DOMContentLoaded", () => {
  const navToggle = document.getElementById("navToggle");
  const navLinks = document.getElementById("navLinks");
  const mainHeader = document.getElementById("mainHeader");

  if (navToggle && navLinks) {
    navToggle.addEventListener("click", () => {
      navToggle.classList.toggle("open");
      navLinks.classList.toggle("open");
    });
  }

  // Header scroll effects
  const onScroll = () => {
    if (window.scrollY > 40) {
      if (mainHeader) mainHeader.classList.add("scrolled");
    } else {
      if (mainHeader) mainHeader.classList.remove("scrolled");
    }
  };
  window.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  // Hero Video Controls
  const video = document.getElementById("heroVideo");
  const audioToggle = document.getElementById("audioToggle");
  const playPauseToggle = document.getElementById("playPauseToggle");
  const soundMutedIcon = document.getElementById("soundMutedIcon");
  const soundActiveIcon = document.getElementById("soundActiveIcon");
  const pauseIcon = document.getElementById("pauseIcon");
  const playIcon = document.getElementById("playIcon");

  if (video) {
    // Ensure video plays
    video.play().catch(() => {
      // Browser autoplay restriction, will play once user interacts
    });

    if (audioToggle) {
      audioToggle.addEventListener("click", () => {
        if (video.muted) {
          video.muted = false;
          if (soundMutedIcon) soundMutedIcon.classList.add("d-none");
          if (soundActiveIcon) soundActiveIcon.classList.remove("d-none");
          audioToggle.classList.add("active");
        } else {
          video.muted = true;
          if (soundMutedIcon) soundMutedIcon.classList.remove("d-none");
          if (soundActiveIcon) soundActiveIcon.classList.add("d-none");
          audioToggle.classList.remove("active");
        }
      });
    }

    if (playPauseToggle) {
      playPauseToggle.addEventListener("click", () => {
        if (video.paused) {
          video.play();
          if (pauseIcon) pauseIcon.classList.remove("d-none");
          if (playIcon) playIcon.classList.add("d-none");
          playPauseToggle.classList.remove("paused");
        } else {
          video.pause();
          if (pauseIcon) pauseIcon.classList.add("d-none");
          if (playIcon) playIcon.classList.remove("d-none");
          playPauseToggle.classList.add("paused");
        }
      });
    }
  }
});
