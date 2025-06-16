<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100" style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-white text-dark shadow-lg" style="border-radius: 1rem;">
          <div class="card-body p-5">
            <h2 class="fw-bold text-center mb-4 text-uppercase">Register</h2>
            <p class="text-muted text-center mb-4">Tạo tài khoản mới của bạn</p>

            <?php if (isset($errors) && is_array($errors)): ?>
              <ul class="mb-4">
                <?php foreach ($errors as $err): ?>
                  <li class="text-danger"><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>

            <form action="/webbanhang/account/save" method="post">
              <!-- Username & Fullname -->
              <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 position-relative">
                  <label for="username" class="form-label fw-semibold">Username</label>
                  <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control form-control-lg rounded-pill ps-5"
                    placeholder="Nhập username..."
                    required
                  />
                  <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="fas fa-user text-primary"></i>
                  </span>
                </div>
                <div class="col-12 col-sm-6 position-relative">
                  <label for="fullname" class="form-label fw-semibold">Fullname</label>
                  <input
                    type="text"
                    id="fullname"
                    name="fullname"
                    class="form-control form-control-lg rounded-pill ps-5"
                    placeholder="Nhập họ tên..."
                    required
                  />
                  <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="fas fa-id-card text-success"></i>
                  </span>
                </div>
              </div>

              <!-- Password & Confirm Password -->
              <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 position-relative">
                  <label for="password" class="form-label fw-semibold">Password</label>
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control form-control-lg rounded-pill ps-5"
                    placeholder="Nhập mật khẩu..."
                    required
                  />
                  <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="fas fa-lock text-danger"></i>
                  </span>
                </div>
                <div class="col-12 col-sm-6 position-relative">
                  <label for="confirmpassword" class="form-label fw-semibold">Confirm Password</label>
                  <input
                    type="password"
                    id="confirmpassword"
                    name="confirmpassword"
                    class="form-control form-control-lg rounded-pill ps-5"
                    placeholder="Xác nhận mật khẩu..."
                    required
                  />
                  <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                    <i class="fas fa-lock text-danger"></i>
                  </span>
                </div>
              </div>

              <!-- Register Button -->
              <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                  Register
                </button>
              </div>

              <!-- Link to Login -->
              <div class="text-center">
                <span class="text-muted">Đã có tài khoản? </span>
                <a href="/webbanhang/account/login" class="fw-semibold text-primary">Login</a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
  /* Card shadow */
  .card.shadow-lg {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.2) !important;
  }

  /* Input hover/focus */
  .form-control:hover,
  .form-control:focus {
    border-color: #2575fc;
    box-shadow: 0 0 0.25rem rgba(37, 117, 252, 0.3);
  }

  /* Icon inside input */
  .position-relative .form-control {
    padding-left: 3rem !important;
  }

  /* Placeholder style */
  ::placeholder {
    color: #6c757d;
    opacity: 1;
  }

  /* Responsive on mobile */
  @media (max-width: 576px) {
    .card {
      margin: 1rem;
    }
  }
</style>

<?php include 'app/views/shares/footer.php'; ?>
