        </main>

        <footer class="px-3 px-lg-4 pb-4 text-muted small">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                <span>&copy; <?= htmlspecialchars((string)date('Y'), ENT_QUOTES, 'UTF-8'); ?> <?= t('footer_text'); ?></span>
                <span>v1.0.0</span>
            </div>
        </footer>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" id="toastContainer"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
