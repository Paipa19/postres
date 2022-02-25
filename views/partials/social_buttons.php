<div class="d-flex justify-content-center">
    <div class="btn-group">
        <a class="btn btn-social-icon bg-gradient-green docs-tooltip" target="_blank"
           href="https://api.whatsapp.com/send?phone=+57<?= $_ENV['SOCIAL_PHONE'] ?>&text=Te%20contacto%20desde%20nuestra%20pagina%20<?= $_ENV['ALIASE_SITE'] ?>">
            <i class="fab fa-whatsapp"></i>
        </a>
        <a class="btn btn-social-icon btn-facebook" target="_blank" href="<?= $_ENV['SOCIAL_FACEBOOK'] ?>">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a class="btn btn-social-icon btn-instagram" target="_blank" href="<?= $_ENV['SOCIAL_INSTAGRAM'] ?>">
            <i class="fab fa-instagram"></i>
        </a>
        <a class="btn btn-social-icon btn-github" target="_blank" href="<?= $_ENV['SOCIAL_GITHUB'] ?>">
            <i class="fab fa-github"></i>
        </a>

    </div>
</div>