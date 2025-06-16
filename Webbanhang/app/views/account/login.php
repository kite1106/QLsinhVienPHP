<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card bg-white text-dark shadow-lg" style="border-radius: 1rem;">
          <div class="card-body p-5">
            <h2 class="fw-bold text-center mb-4 text-uppercase">Login</h2>
            <p class="text-muted text-center mb-4">Please enter your login and password!</p>

            <form action="/webbanhang/account/checklogin" method="post">
              <!-- Username Field -->
              <div class="form-floating mb-4 position-relative">
                <input
                  type="text"
                  name="username"
                  id="username"
                  class="form-control form-control-lg rounded-pill ps-5"
                  placeholder="Username"
                  required
                />
                <label for="username" class="ms-3">Username</label>
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                  <i class="fas fa-user text-primary"></i>
                </span>
              </div>

              <!-- Password Field -->
              <div class="form-floating mb-4 position-relative">
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control form-control-lg rounded-pill ps-5"
                  placeholder="Password"
                  required
                />
                <label for="password" class="ms-3">Password</label>
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                  <i class="fas fa-lock text-primary"></i>
                </span>
              </div>

              <!-- Forgot Password Link -->
              <div class="text-end mb-4">
                <a href="#!" class="text-primary small">Forgot password?</a>
              </div>

              <!-- Login Button -->
              <div class="d-grid mb-4">
                <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                  Login
                </button>
              </div>

              <!-- Social Icons -->
              <div class="d-flex justify-content-center mb-4">
                <a href="#!" class="text-primary mx-3 social-icon"><i class="fab fa-facebook-f fa-lg"></i></a>
                <a href="#!" class="text-danger mx-3 social-icon"><i class="fab fa-google fa-lg"></i></a>
                <a href="#!" class="text-info mx-3 social-icon"><i class="fab fa-twitter fa-lg"></i></a>
              </div>

              <!-- Sign Up Link -->
              <div class="text-center">
                <p class="mb-0 text-muted">Don't have an account?
                  <a href="/webbanhang/account/register" class="text-primary fw-bold"> Sign Up</a>
                </p>
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
    border-color: #00bfff;
    box-shadow: 0 0 0.25rem rgba(0, 191, 255, 0.3);
  }

  /* Icon inside input */
  .position-relative .form-control {
    padding-left: 3rem !important;
  }

  /* Social icon hover */
  .social-icon {
    font-size: 1.25rem;
    transition: transform 0.3s, color 0.3s;
  }
  .social-icon:hover {
    transform: scale(1.2);
    color: #007bff;
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
