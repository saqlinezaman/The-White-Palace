  </main>
    <!--end main content-->

    <!--start overlay-->
    <div class="overlay btn-toggle-menu"></div>
    <!--end overlay-->

    <!-- Search Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search...">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!--start theme customization-->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="ThemeCustomizer" aria-labelledby="ThemeCustomizerLable">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="ThemeCustomizerLable">Theme Customizer</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <h6 class="mb-0">Theme Variation</h6>
            <hr>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="LightTheme" value="option1">
                <label class="form-check-label" for="LightTheme">Light</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="DarkTheme" value="option2" checked>
                <label class="form-check-label" for="DarkTheme">Dark</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="SemiDarkTheme" value="option3">
                <label class="form-check-label" for="SemiDarkTheme">Semi Dark</label>
            </div>
            <hr>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="MinimalTheme" value="option3">
                <label class="form-check-label" for="MinimalTheme">Minimal Theme</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="ShadowTheme" value="option4">
                <label class="form-check-label" for="ShadowTheme">Shadow Theme</label>
            </div>
        </div>
    </div>
    <!--end theme customization-->

    <!--plugins-->
    <script src="<?= BASE_URL ?>/assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <script src="<?= BASE_URL ?>/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/plugins/apex/apexcharts.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/index.js"></script>
    
    <!--BS Scripts-->
    <script src="<?= BASE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script src="<?= BASE_URL ?>/assets/js/main.js"></script>

    <script>
        // Theme customization functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Theme switching logic
            const themeRadios = document.querySelectorAll('input[name="inlineRadioOptions"]');
            
            themeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.id === 'LightTheme') {
                        document.documentElement.setAttribute('data-bs-theme', 'light');
                    } else if (this.id === 'DarkTheme') {
                        document.documentElement.setAttribute('data-bs-theme', 'dark');
                    } else if (this.id === 'SemiDarkTheme') {
                        document.documentElement.setAttribute('data-bs-theme', 'semi-dark');
                    } else if (this.id === 'MinimalTheme') {
                        document.documentElement.setAttribute('data-bs-theme', 'minimal');
                    } else if (this.id === 'ShadowTheme') {
                        document.documentElement.setAttribute('data-bs-theme', 'shadow');
                    }
                });
            });
            
            // Dark mode toggle
            const darkModeToggle = document.querySelector('.dark-mode-icon');
            darkModeToggle.addEventListener('click', function() {
                const currentTheme = document.documentElement.getAttribute('data-bs-theme');
                if (currentTheme === 'dark') {
                    document.documentElement.setAttribute('data-bs-theme', 'light');
                    document.querySelector('#LightTheme').checked = true;
                } else {
                    document.documentElement.setAttribute('data-bs-theme', 'dark');
                    document.querySelector('#DarkTheme').checked = true;
                }
            });
        });
    </script>
</body>
</html>