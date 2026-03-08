<?php
/**
 * FILE: pages/contact.php
 * PURPOSE: Contact page with store phone, WhatsApp, address, map placeholder.
 */

// Load master configuration
require_once __DIR__ . '/../config/config.php';

global $current_language, $is_rtl, $lang;

$page_title = translate('contact_us') . ' - ' . $lang['site_name'];

require_once __DIR__ . '/../includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    <!-- Contact Sidebar -->
                    <div class="col-md-5 bg-primary text-white p-4 p-md-5">
                        <h1 class="h3 fw-bold mb-4"><?= translate('contact_us') ?></h1>
                        <p class="mb-5 small opacity-75">
                            Besoin d'aide ? Contactez-nous via l'un des canaux suivants.
                        </p>
                        
                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-geo-alt fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Adresse</h6>
                                <p class="small mb-0">Safi, Maroc</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <i class="bi bi-telephone fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">Téléphone</h6>
                                <p class="small mb-0"><?= STORE_PHONE_DISPLAY ?></p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-5">
                            <i class="bi bi-whatsapp fs-3 me-3"></i>
                            <div>
                                <h6 class="fw-bold mb-0">WhatsApp</h6>
                                <p class="small mb-0"><?= STORE_PHONE_DISPLAY ?></p>
                            </div>
                        </div>

                        <div class="mt-auto">
                            <h6 class="fw-bold mb-3">Horaires</h6>
                            <p class="small mb-0">Lun - Sam : 08:00 - 22:00</p>
                            <p class="small mb-0">Dimanche : 09:00 - 18:00</p>
                        </div>
                    </div>

                    <!-- Contact Form / Action -->
                    <div class="col-md-7 p-4 p-md-5 bg-white">
                        <h2 class="h4 fw-bold mb-4">Discuter avec nous</h2>
                        <p class="text-muted small mb-4">
                            La commande via WhatsApp est simple et rapide. Cliquez sur le bouton ci-dessous pour démarrer une conversation.
                        </p>

                        <div class="d-grid gap-3">
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', STORE_WHATSAPP_NUMBER) ?>" class="btn btn-whatsapp text-white py-3 fw-bold rounded-3 shadow-sm" target="_blank">
                                <i class="bi bi-whatsapp me-2 fs-5"></i> COMMANDER VIA WHATSAPP
                            </a>
                            <a href="tel:<?= STORE_PHONE_DISPLAY ?>" class="btn btn-outline-primary py-3 fw-bold rounded-3">
                                <i class="bi bi-telephone me-2"></i> NOUS APPELER
                            </a>
                        </div>

                        <div class="mt-5 pt-4 border-top">
                            <h3 class="h6 fw-bold mb-3">Retrouvez-nous sur les réseaux</h3>
                            <div class="d-flex gap-3">
                                <a href="#" class="btn btn-light rounded-circle"><i class="bi bi-facebook fs-5"></i></a>
                                <a href="#" class="btn btn-light rounded-circle"><i class="bi bi-instagram fs-5"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-whatsapp { background-color: var(--color-whatsapp); border-color: var(--color-whatsapp); }
.btn-whatsapp:hover { background-color: #20bd5a; border-color: #20bd5a; }
</style>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
