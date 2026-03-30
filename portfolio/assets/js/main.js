/**
 * PORTOFOLIO - main.js
 * Native JS + AJAX untuk mengambil data dari PHP backend
 */

// ===========================
// LOADER
// ===========================
(function initLoader() {
  const loader     = document.getElementById('loader');
  const countEl    = document.getElementById('loaderCount');
  const body       = document.body;

  body.classList.add('loading');

  let count = 0;
  const interval = setInterval(() => {
    count += Math.floor(Math.random() * 12) + 4;
    if (count >= 100) {
      count = 100;
      clearInterval(interval);
      setTimeout(() => {
        loader.classList.add('done');
        body.classList.remove('loading');
        initRevealObserver();
        loadProjects('semua');
        loadSkills();
      }, 300);
    }
    countEl.textContent = count + '%';
  }, 80);
})();

// ===========================
// CUSTOM CURSOR
// ===========================
const cursor         = document.getElementById('cursor');
const cursorFollower = document.getElementById('cursorFollower');

if (cursor && cursorFollower) {
  let mouseX = 0, mouseY = 0;
  let followerX = 0, followerY = 0;

  document.addEventListener('mousemove', (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    cursor.style.left = mouseX + 'px';
    cursor.style.top  = mouseY + 'px';
  });

  function animateFollower() {
    followerX += (mouseX - followerX) * 0.12;
    followerY += (mouseY - followerY) * 0.12;
    cursorFollower.style.left = followerX + 'px';
    cursorFollower.style.top  = followerY + 'px';
    requestAnimationFrame(animateFollower);
  }
  animateFollower();

  const hoverables = document.querySelectorAll('a, button, .project-card, .filter-btn, .tool-tag');
  hoverables.forEach(el => {
    el.addEventListener('mouseenter', () => {
      cursor.classList.add('hovering');
      cursorFollower.classList.add('hovering');
    });
    el.addEventListener('mouseleave', () => {
      cursor.classList.remove('hovering');
      cursorFollower.classList.remove('hovering');
    });
  });
}

// ===========================
// NAVIGATION
// ===========================
const nav        = document.getElementById('nav');
const navToggle  = document.getElementById('navToggle');

window.addEventListener('scroll', () => {
  if (window.scrollY > 50) {
    nav.classList.add('scrolled');
  } else {
    nav.classList.remove('scrolled');
  }
});

// Mobile menu
let mobileMenu = null;

function createMobileMenu() {
  mobileMenu = document.createElement('div');
  mobileMenu.className = 'nav-mobile-menu';
  const links = ['Tentang', 'Proyek', 'Keahlian', 'Kontak'];
  const hrefs = ['#about', '#projects', '#skills', '#contact'];
  links.forEach((text, i) => {
    const a = document.createElement('a');
    a.href  = hrefs[i];
    a.className = 'nav-link';
    a.textContent = text;
    a.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
    });
    mobileMenu.appendChild(a);
  });
  document.body.appendChild(mobileMenu);
}

navToggle && navToggle.addEventListener('click', () => {
  if (!mobileMenu) createMobileMenu();
  mobileMenu.classList.toggle('open');
});

// ===========================
// REVEAL ON SCROLL
// ===========================
function initRevealObserver() {
  const revealEls = document.querySelectorAll(
    '.section-label, .about-grid, .skills-layout, .contact-layout, .footer, .projects-header'
  );

  revealEls.forEach(el => el.classList.add('reveal'));

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  revealEls.forEach(el => observer.observe(el));
}

// ===========================
// AJAX HELPER
// ===========================
/**
 * Fungsi AJAX generik
 * @param {string} url
 * @param {string} method
 * @param {object|null} data
 * @param {function} onSuccess
 * @param {function} onError
 */
function ajax(url, method, data, onSuccess, onError) {
  const xhr = new XMLHttpRequest();
  xhr.open(method, url, true);
  xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

  if (method === 'POST') {
    xhr.setRequestHeader('Content-Type', 'application/json');
  }

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status >= 200 && xhr.status < 300) {
        try {
          const parsed = JSON.parse(xhr.responseText);
          onSuccess(parsed);
        } catch (e) {
          onSuccess(xhr.responseText);
        }
      } else {
        if (onError) onError(xhr.status, xhr.responseText);
      }
    }
  };

  xhr.onerror = function () {
    if (onError) onError(0, 'Network error');
  };

  xhr.send(data ? JSON.stringify(data) : null);
}

// ===========================
// LOAD PROJECTS (AJAX)
// ===========================
let currentFilter = 'semua';
let currentPage   = 1;

function loadProjects(filter, page, append) {
  currentFilter = filter;
  const grid = document.getElementById('projectsGrid');

  if (!append) {
    grid.innerHTML = '<div class="projects-loading"><div class="loading-spinner"></div><span>Memuat proyek...</span></div>';
    currentPage = 1;
  }

  const url = `api/projects.php?filter=${encodeURIComponent(filter)}&page=${page || 1}`;

  ajax(url, 'GET', null,
    function (data) {
      if (!append) {
        grid.innerHTML = '';
      } else {
        const loader = grid.querySelector('.projects-loading');
        if (loader) loader.remove();
      }

      if (!data.projects || data.projects.length === 0) {
        grid.innerHTML = '<div class="projects-loading"><span>Tidak ada proyek ditemukan.</span></div>';
        return;
      }

      data.projects.forEach((project, idx) => {
        const card = createProjectCard(project);
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px)';
        grid.appendChild(card);

        setTimeout(() => {
          card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, idx * 80);
      });

      // Sembunyikan tombol load more jika tidak ada lagi
      const loadMoreBtn = document.getElementById('loadMoreBtn');
      if (loadMoreBtn) {
        loadMoreBtn.style.display = data.hasMore ? 'inline-flex' : 'none';
      }
    },
    function (status, err) {
      // Fallback: tampilkan data dummy jika PHP tidak tersedia
      grid.innerHTML = '';
      const dummyProjects = getDummyProjects(filter);
      dummyProjects.forEach((project, idx) => {
        const card = createProjectCard(project);
        card.style.opacity = '0';
        card.style.transform = 'translateY(24px)';
        grid.appendChild(card);
        setTimeout(() => {
          card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
          card.style.opacity = '1';
          card.style.transform = 'translateY(0)';
        }, idx * 80);
      });

      const loadMoreBtn = document.getElementById('loadMoreBtn');
      if (loadMoreBtn) loadMoreBtn.style.display = 'none';

      console.warn('PHP tidak tersedia, menggunakan data statis.', status);
    }
  );
}

function createProjectCard(project) {
  const card = document.createElement('div');
  card.className = 'project-card' + (project.featured ? ' featured' : '');

  const gradients = {
    web: 'linear-gradient(135deg, #1a1a2e, #16213e)',
    ui:  'linear-gradient(135deg, #0d1117, #1c2526)',
    app: 'linear-gradient(135deg, #1a0a0a, #2e1a1a)',
  };
  const bg = gradients[project.category] || gradients.web;

  card.innerHTML = `
    <div class="project-thumb">
      <div class="project-thumb-bg" style="background: ${bg};">
        <span>${project.emoji || '💻'}</span>
      </div>
      <div class="project-tag-overlay">
        <span class="project-tag">${project.category.toUpperCase()}</span>
      </div>
    </div>
    <div class="project-body">
      <h3 class="project-title">${escapeHtml(project.title)}</h3>
      <p class="project-desc">${escapeHtml(project.description)}</p>
      <div class="project-stack">
        ${(project.stack || []).map(s => `<span class="stack-tag">${escapeHtml(s)}</span>`).join('')}
      </div>
      <div class="project-links">
        ${project.demo  ? `<a href="${escapeHtml(project.demo)}"  class="project-link" target="_blank">Demo →</a>` : ''}
        ${project.github ? `<a href="${escapeHtml(project.github)}" class="project-link" target="_blank">GitHub →</a>` : ''}
      </div>
    </div>`;

  return card;
}

function escapeHtml(str) {
  return String(str)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;');
}

// Data dummy fallback jika PHP tidak tersedia
function getDummyProjects(filter) {
  const all = [
    { title: 'SiMarket — E-Commerce Platform', description: 'Platform belanja online lengkap dengan cart, checkout, dan dashboard admin real-time.', category: 'web', emoji: '🛒', featured: true, stack: ['PHP', 'MySQL', 'JS', 'AJAX', 'Bootstrap'], demo: '#', github: '#' },
    { title: 'MedTrack — Health Dashboard', description: 'Dashboard monitoring kesehatan pribadi dengan visualisasi data interaktif.', category: 'ui', emoji: '💊', featured: false, stack: ['Figma', 'React', 'Chart.js'], demo: '#', github: '#' },
    { title: 'TaskFlow — Project Manager', description: 'Aplikasi manajemen proyek Kanban dengan fitur kolaborasi tim real-time.', category: 'app', emoji: '📋', featured: false, stack: ['Laravel', 'Vue.js', 'WebSocket'], demo: '#', github: '#' },
    { title: 'LinguaAI — Language Learning', description: 'Platform belajar bahasa berbasis AI dengan gamifikasi dan pelacakan kemajuan.', category: 'app', emoji: '🌍', featured: false, stack: ['Python', 'FastAPI', 'React Native'], demo: '#', github: '#' },
    { title: 'UrbanSpace — Arsitektur Studio', description: 'Website portofolio studio arsitektur dengan galeri 3D dan animasi halaman.', category: 'web', emoji: '🏢', featured: false, stack: ['HTML', 'CSS', 'GSAP', 'Three.js'], demo: '#', github: '#' },
    { title: 'FoodieApp — Restoran UI Kit', description: 'UI Kit lengkap untuk aplikasi pemesanan makanan dengan 80+ komponen siap pakai.', category: 'ui', emoji: '🍜', featured: false, stack: ['Figma', 'Adobe XD'], demo: '#', github: '#' },
  ];

  if (filter === 'semua') return all;
  return all.filter(p => p.category === filter);
}

// Filter tombol
document.querySelectorAll('.filter-btn').forEach(btn => {
  btn.addEventListener('click', function () {
    document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
    this.classList.add('active');
    loadProjects(this.dataset.filter);
  });
});

// Load more
const loadMoreBtn = document.getElementById('loadMoreBtn');
if (loadMoreBtn) {
  loadMoreBtn.addEventListener('click', function () {
    currentPage++;
    this.querySelector('span').textContent = 'Memuat...';
    loadProjects(currentFilter, currentPage, true);
    setTimeout(() => {
      if (this.querySelector('span')) {
        this.querySelector('span').textContent = 'Muat Lebih Banyak';
      }
    }, 1500);
  });
}

// ===========================
// LOAD SKILLS (AJAX)
// ===========================
function loadSkills() {
  const container = document.getElementById('skillsContainer');

  ajax('api/skills.php', 'GET', null,
    function (data) {
      renderSkills(data.skills || data);
    },
    function () {
      // Fallback data dummy
      renderSkills([
        { name: 'HTML & CSS',        level: 95 },
        { name: 'JavaScript (ES6+)', level: 85 },
        { name: 'PHP',               level: 80 },
        { name: 'MySQL & Database',  level: 78 },
        { name: 'UI/UX Design',      level: 88 },
        { name: 'React.js',          level: 72 },
        { name: 'Laravel Framework', level: 75 },
        { name: 'Git & Version Control', level: 82 },
      ]);
    }
  );
}

function renderSkills(skills) {
  const container = document.getElementById('skillsContainer');
  container.innerHTML = '';

  skills.forEach((skill, idx) => {
    const item = document.createElement('div');
    item.className = 'skill-item';
    item.innerHTML = `
      <div class="skill-header">
        <span class="skill-name">${escapeHtml(skill.name)}</span>
        <span class="skill-pct">${skill.level}%</span>
      </div>
      <div class="skill-bar">
        <div class="skill-fill" data-width="${skill.level}" style="width: 0%"></div>
      </div>`;
    container.appendChild(item);
  });

  // Animate bars on scroll
  const skillObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const fills = entry.target.querySelectorAll('.skill-fill');
        fills.forEach((fill, idx) => {
          setTimeout(() => {
            fill.style.width = fill.dataset.width + '%';
          }, idx * 100);
        });
        skillObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.2 });

  skillObserver.observe(container);
}

// ===========================
// CONTACT FORM (AJAX POST)
// ===========================
const contactForm = document.getElementById('contactForm');

if (contactForm) {
  contactForm.addEventListener('submit', function (e) {
    e.preventDefault();

    const name    = document.getElementById('name');
    const email   = document.getElementById('email');
    const message = document.getElementById('message');
    const submitBtn = document.getElementById('submitBtn');
    const formSuccess = document.getElementById('formSuccess');
    const formErrorMsg = document.getElementById('formErrorMsg');

    // Reset errors
    clearErrors();
    formSuccess.style.display = 'none';
    formErrorMsg.style.display = 'none';

    let valid = true;

    if (!name.value.trim()) {
      showError('name', 'Nama tidak boleh kosong.');
      valid = false;
    }

    if (!email.value.trim()) {
      showError('email', 'Email tidak boleh kosong.');
      valid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value.trim())) {
      showError('email', 'Format email tidak valid.');
      valid = false;
    }

    if (!message.value.trim()) {
      showError('message', 'Pesan tidak boleh kosong.');
      valid = false;
    } else if (message.value.trim().length < 10) {
      showError('message', 'Pesan minimal 10 karakter.');
      valid = false;
    }

    if (!valid) return;

    // Tampilkan loading state
    const btnText    = submitBtn.querySelector('.btn-text');
    const btnLoading = submitBtn.querySelector('.btn-loading');
    btnText.style.display    = 'none';
    btnLoading.style.display = 'inline';
    submitBtn.disabled       = true;

    const payload = {
      name:    name.value.trim(),
      email:   email.value.trim(),
      subject: document.getElementById('subject').value,
      message: message.value.trim(),
    };

    ajax('api/contact.php', 'POST', payload,
      function (res) {
        btnText.style.display    = 'inline';
        btnLoading.style.display = 'none';
        submitBtn.disabled       = false;

        if (res.success) {
          contactForm.reset();
          formSuccess.style.display = 'flex';
          setTimeout(() => { formSuccess.style.display = 'none'; }, 5000);
        } else {
          formErrorMsg.textContent   = res.message || 'Terjadi kesalahan. Coba lagi.';
          formErrorMsg.style.display = 'block';
        }
      },
      function (status) {
        btnText.style.display    = 'inline';
        btnLoading.style.display = 'none';
        submitBtn.disabled       = false;

        // Simulasi sukses jika PHP tidak tersedia (demo)
        if (status === 0 || status === 404) {
          contactForm.reset();
          formSuccess.style.display = 'flex';
          setTimeout(() => { formSuccess.style.display = 'none'; }, 5000);
        } else {
          formErrorMsg.textContent   = 'Gagal mengirim pesan. Pastikan koneksi Anda aktif.';
          formErrorMsg.style.display = 'block';
        }
      }
    );
  });
}

function showError(field, msg) {
  const input = document.getElementById(field);
  const errEl = document.getElementById(field + 'Error');
  if (input)  input.classList.add('error');
  if (errEl)  errEl.textContent = msg;
}

function clearErrors() {
  ['name', 'email', 'message'].forEach(f => {
    const input = document.getElementById(f);
    const errEl = document.getElementById(f + 'Error');
    if (input)  input.classList.remove('error');
    if (errEl)  errEl.textContent = '';
  });
}

// ===========================
// DOWNLOAD CV (demo)
// ===========================
const downloadCv = document.getElementById('downloadCv');
if (downloadCv) {
  downloadCv.addEventListener('click', function (e) {
    e.preventDefault();
    ajax('api/download.php', 'GET', null,
      function (res) {
        if (res.url) {
          const a = document.createElement('a');
          a.href     = res.url;
          a.download = 'CV_Raka_Andhika.pdf';
          a.click();
        } else {
          alert('File CV belum tersedia. Silakan hubungi saya langsung.');
        }
      },
      function () {
        alert('File CV belum tersedia. Silakan hubungi saya langsung.');
      }
    );
  });
}

// ===========================
// SMOOTH NAV SCROLL
// ===========================
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  });
});
