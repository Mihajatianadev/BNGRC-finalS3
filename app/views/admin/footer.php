<link rel="stylesheet" href="/css/accueil.css">
<style>
    .vitrine-footer {
        background-color: #05113d7e;
        color: white;
        padding: 20px 0;
        font-size: 14px;
        margin-top: 10px;
    }

    .vitrine-footer .vitrine-footer-inner {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .vitrine-footer .footer-logo {
        max-width: 150px;
    }

    .vitrine-footer .footer-text {
        margin-top: 10px;
    }

    .vitrine-footer .vitrine-footer-right {
        display: flex;
        gap: 15px;
    }

    .vitrine-footer .vitrine-footer-link {
        color: white;
        text-decoration: none;
        transition: color 0.3s;
    }

    .vitrine-footer .vitrine-footer-link:hover {
        color: #ffffffff;
    }

    .vitrine-footer .vitrine-footer-bottom {
        text-align: center;
        margin-top: 20px;
        font-size: 12px;
    }
</style>
<footer class="vitrine-footer">
    <div class="container">
        <div class="vitrine-footer-inner">

            <div class="vitrine-footer-left">
                <img src="/assets/images/bngrc.png"  width="35" height="35" alt="BNGRC Logo" style="border-radius: 10px; object-fit: contain;">
                <div class="footer-text">
                    <strong>BNGRC</strong><br>
                    Coordination des actions de secours et distribution des aides humanitaires à Madagascar.
                </div>
            </div>

            <div class="vitrine-footer-right">
                <a class="vitrine-footer-link" href="/login">4106</a>
                <a class="vitrine-footer-link" href="#">4132</a>
                <a class="vitrine-footer-link" href="#">4381</a>
            </div>

        </div>

        <div class="vitrine-footer-bottom">
            © <?= date('Y') ?> BNGRC — Tous droits réservés
        </div>
    </div>
</footer>