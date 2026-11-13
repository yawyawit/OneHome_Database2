        </main>
        <?php if (!empty($is_admin_layout)): ?>
            <footer class="app-footer">
                <div class="container-fluid">
                    <span>One Home</span>
                    <span>Home services made simpler <span class="footer-dot">&middot;</span> &copy; <?= date('Y') ?></span>
                </div>
            </footer>
            </div>
        </div>
        <?php else: ?>
            <footer class="app-footer">
                <div class="container-fluid">
                    <span>One Home</span>
                    <span>Home services made simpler <span class="footer-dot">&middot;</span> &copy; <?= date('Y') ?></span>
                </div>
            </footer>
        <?php endif; ?>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="<?= url('js/script.js') ?>"></script>
    </body>

</html>
