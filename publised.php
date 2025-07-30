<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upload Drawing</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <!-- Google Fonts: Inter for clean text -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet"/>
  <!-- AOS Animate on Scroll -->
  <link href="https://unpkg.com/aos@next/dist/aos.css" rel="stylesheet" />

  <style>
    body {
      min-height: 100vh;
      background: linear-gradient(120deg, #1a1a2e 0%, #16213e 40%, #ffc93c 100%);
      background-size: 200% 200%;
      animation: gradientMove 12s ease-in-out infinite;
      font-family: 'Inter', Arial, sans-serif;
      display: flex;
      align-items: flex-start;
      justify-content: center;
      padding: 65px 10px;
      position: relative;
    }

    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .upload-form-glass {
      position: relative;
      z-index: 1;
      max-width: 440px;
      width: 100%;
      margin: 0 auto;
      padding: 36px 28px 28px 28px;
      background: rgba(255,255,255,0.16);
      border-radius: 1.2rem;
      box-shadow: 0 12px 30px rgba(24,24,61,0.4);
      backdrop-filter: blur(10px);
      color: #f1f1f1;
      text-align: center;
      border: 1.5px solid rgba(194,206,255,0.09);
    }

    .upload-form-glass h2 {
      font-weight: 700;
      color: #ffc93c;
      font-size: 2.1rem;
      margin-bottom: 22px;
      letter-spacing: 0.04em;
      text-shadow: 1px 4px 18px #14002e77;
      user-select: none;
    }

    label {
      font-weight: 600;
      font-size: 1rem;
      color: #ffc93c;
      margin-bottom: 5px;
      display: block;
      text-align: left;
      user-select: none;
    }

    .form-control, .form-select {
      background: rgba(36,37,67,0.22);
      border: none;
      color: #fff;
      font-size: 1rem;
      border-radius: 0.7rem;
      margin-bottom: 18px;
      transition: background 0.3s, box-shadow 0.3s;
      padding: 11px;
      height: 44px;
    }

    textarea.form-control {
      min-height: 78px;
      height: auto;
      resize: vertical;
      line-height: 1.4;
      padding-top: 10px;
      padding-bottom: 10px;
    }

    .form-control:focus, .form-select:focus, textarea:focus {
      background: rgba(255,255,255,0.25);
      box-shadow: 0 0 13px #ffc93cbb, 0 0 0 2px #ffc93c66;
      color: #000;
      outline: none;
    }

    ::placeholder { color: #ececec; opacity: 0.8; }

    /* File input tweak */
    input[type="file"].form-control {
      background: transparent;
      color: #ffc93c;
      height: auto;
      margin-bottom: 18px;
      border-radius: 0;
      border: none;
    }

    /* Neon Glow Button */
    .btn-submit {
      width: 100%;
      background: linear-gradient(90deg, #ffc93c 0%, #ffba08 100%);
      color: #20103d;
      font-weight: 700;
      font-size: 1.08rem;
      border: none;
      border-radius: 0.8rem;
      padding: 13px 0;
      margin-top: 2px;
      letter-spacing: 0.04em;
      box-shadow: 0 0 18px 0 #ffc93c70;
      cursor: pointer;
      transition: box-shadow 0.25s, background 0.25s, color 0.25s;
      text-shadow: none;
    }

    .btn-submit:hover, .btn-submit:focus {
      box-shadow: 0 0 32px 0 #ffc93cbb;
      background: linear-gradient(90deg, #ffba08 0%, #ffc93c 100%);
      color: #000;
      outline: none;
    }

    /* Animations for entrance */
    .upload-form-glass > form > *:not(:last-child) {
      opacity: 0;
      transform: translateY(30px);
      animation: fadeinUp 1.1s cubic-bezier(0.16,1,0.3,1) forwards;
      animation-delay: calc(var(--order, 1) * 0.08s + 0.07s);
    }
    @keyframes fadeinUp {
      to { opacity: 1; transform: none; }
    }
    /* Delay via inline style on order */

    /* Responsive adjustments */
    @media (max-width: 490px) {
      .upload-form-glass { padding: 22px 9px; }
      .btn-submit { font-size: 0.98rem; }
    }
  </style>
</head>
<body>
  <div class="upload-form-glass shadow-lg" data-aos="zoom-in" data-aos-duration="900">
    <h2 data-aos="fade-down" data-aos-delay="100">Upload Drawing</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data" novalidate>
      <label for="name" style="--order:1;">Full Name:</label>
      <input type="text" class="form-control" name="name" id="name" required placeholder="Your full name" style="--order:2;" />

      <label for="contact" style="--order:3;">Contact Number:</label>
      <input 
        type="text" 
        class="form-control"
        name="contact" 
        id="contact" 
        pattern="[0-9]{10}" 
        title="Enter a 10-digit number" 
        required 
        placeholder="10-digit number"
        style="--order:4;"
      />

      <label for="type" style="--order:5;">Type of Drawing:</label>
      <select class="form-select" name="type" id="type" required style="--order:6;">
        <option value="" selected disabled>-- Select Type --</option>
        <option value="Pencil Sketch">Pencil Sketch</option>
        <option value="Watercolor">Watercolor</option>
        <option value="Digital Art">Digital Art</option>
        <option value="Oil Painting">Oil Painting</option>
        <option value="Cartoon/Anime">Cartoon/Anime</option>
        <option value="Other">Other</option>
      </select>

      <label for="description" style="--order:7;">Description:</label>
      <textarea class="form-control" name="description" id="description" rows="3" required placeholder="Brief description" style="--order:8;"></textarea>

      <label for="image" style="--order:9;">Upload Drawing Image:</label>
      <input class="form-control" type="file" name="image" id="image" accept="image/*" required style="--order:10;"/>

      <button type="submit" name="submit" class="btn btn-submit" style="--order:11;" data-aos="fade-up" data-aos-delay="150">Submit</button>
    </form>
  </div>

  <!-- AOS JS -->
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>AOS.init({ duration: 900, once: true });</script>
  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
<script>
(() => {
  'use strict'

  // Fetch all forms with class 'needs-validation'
  const forms = document.querySelectorAll('.needs-validation')

  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      // Custom check for description min length 10
      const description = form.querySelector('#description')
      if (description && description.value.trim().length < 10) {
        description.setCustomValidity("Description must be at least 10 characters.")
      } else if (description) {
        description.setCustomValidity("")
      }

      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()
</script>

</html>
