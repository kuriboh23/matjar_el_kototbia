<?php
/**
 * FILE: admin/order_print.php
 * PURPOSE: Print-friendly version of an order receipt.
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/admin_auth_check.php';

$order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order = get_order_by_id($order_id);

if (!$order) {
    die("Commande non trouvée.");
}

    $order_items = get_order_items($order_id);
    $livreur = $order['order_livreur_id'] ? get_livreur_by_id($order['order_livreur_id']) : null;

    // Build WhatsApp Messages
    $customer_msg = build_whatsapp_message_for_customer($order, $livreur);
    $customer_phone_clean = clean_phone_number($order['order_customer_phone']);
    // Ensure international format (assuming Morocco +212 if starting with 0)
    if (str_starts_with($customer_phone_clean, '0')) {
        $customer_phone_clean = '212' . substr($customer_phone_clean, 1);
    }
    $customer_wa_url = "https://wa.me/" . $customer_phone_clean . "?text=" . rawurlencode($customer_msg);

    $livreur_wa_url = "#";
    if ($livreur && $livreur['livreur_phone']) {
        $livreur_msg = build_whatsapp_message_for_livreur($order, $livreur);
        $livreur_phone_clean = clean_phone_number($livreur['livreur_phone']);
        if (str_starts_with($livreur_phone_clean, '0')) {
            $livreur_phone_clean = '212' . substr($livreur_phone_clean, 1);
        }
        $livreur_wa_url = "https://wa.me/" . $livreur_phone_clean . "?text=" . rawurlencode($livreur_msg);
    }
    ?>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon_<?= $order['order_number'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        :root {
            --princeton-orange: #ff8200;
            --carbon-black: #171711;
            --gray-bg: #f8fafc;
            --gray-border: #e2e8f0;
            
        }

        body {
            margin: 0;
            padding: 40px;
            font-family: 'Plus Jakarta Sans' ;
            background-color: var(--gray-bg);
            color: var(--carbon-black);
            display: flex;
            justify-content: center;
        }

        /* The Receipt Container */
        .receipt-container {
            background: white;
            width: 800px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            border: 1px solid var(--gray-border);
            position: relative;
        }

        /* Print Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--carbon-black);
            padding-bottom: 20px;
            margin-bottom: 20px;
        }

        table th:first-child{
        border-radius:10px 0 0 10px;
        }

        table th:last-child{
        border-radius:0 10px 10px 0;
        }

        .logo-area h1 {
            margin: 0;
            font-weight: 900;
            font-size: 24px;
            letter-spacing: -1px;
        }
        .logo-area h1 span { color: var(--princeton-orange); }
        .logo-area p { margin: 5px 0 0; font-size: 13px; font-weight: 700; color: #64748b; }

        .order-meta { text-align: right; }
        .order-meta h2 { margin: 0; font-size: 18px; font-weight: 800; }
        .order-meta p { margin: 2px 0 0; font-size: 11px; font-weight: 600; color: #64748b; opacity: 0.8;}

        /* Customer Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--princeton-orange);
            margin-bottom: 8px;
            font-weight: 800;
        }
        .info-section p { margin: 0; font-weight: 700; line-height: 1.4; font-size: 14px; }

        /* Items Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            text-align: left;
            padding: 10px 12px;
            background: var(--carbon-black);
            color: white;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        td {
            padding: 12px 12px;
            border-bottom: 1px solid var(--gray-border);
            font-weight: 600;
            font-size: 13px;
        }

        .qty-cell { color: var(--princeton-orange); font-weight: 800; }

        /* Totals Area */
        .totals-wrapper {
            display: flex;
            justify-content: flex-end;
        }

        .totals-table {
            width: 250px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 6px 0;
            font-size: 13px;
        }

        .total-row.grand-total {
            border-top: 2px solid var(--carbon-black);
            margin-top: 8px;
            padding-top: 12px;
            font-size: 18px;
            font-weight: 900;
        }

        /* Footer / Terms */
        .receipt-footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px dashed var(--gray-border);
            text-align: center;
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
        }

        .action-bar {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            gap: 15px;
            z-index: 1000;
        }

        .print-btn {
            background: var(--carbon-black);
            color: white;
            padding: 15px 30px;
            border-radius: 50px;
            font-weight: 800;
            border: none;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        /* PRINT STYLES */
        @media print {
            @page {
                size: auto;   /* auto is the initial value */
                margin: 0;    /* this affects the margin in the printer settings and hides the URL */
            }
            body { 
                background: white; 
                padding: 10mm; /* Add content padding inside the printed page */
            }
            .receipt-container { 
                box-shadow: none; 
                border: none; 
                width: 100%; 
                padding: 0;
                border-radius: 0;
            }
            .action-bar { display: none; }
            th { background: #f0f0f0 !important; color: black !important; border: 1px solid #000; }
            td { border: 1px solid #eee; }
            
            /* Prevent items from being cut across pages */
            tr { page-break-inside: avoid; }
            .header, .info-grid, .totals-wrapper, .receipt-footer { page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    <div class="receipt-container">
        <div class="header">
            <div class="logo-area">
                <h1>MATJAR<span>.</span>KOTOBIA</h1>
            </div>
            <div class="order-meta">
                <h2 style="color: var(--princeton-orange);">BON DE LIVRAISON</h2>
                <p>#<?= $order['order_number'] ?></p>
                <p><?= date('d/m/Y H:i', strtotime($order['order_created_at'])) ?></p>
            </div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <h3>Client / العميل</h3>
                <p style="font-size: 16px;"><?= htmlspecialchars($order['order_customer_name']) ?></p>
                <p><?= htmlspecialchars($order['order_customer_phone']) ?></p>
            </div>
            <div class="info-section">
                <h3>Livraison / توصيل</h3>
                <p><strong><?= htmlspecialchars($order['order_customer_address']) ?></strong></p>
                <p style="font-size: 12px; color: #717171;"><?= htmlspecialchars($order['order_customer_city']) ?></p>
            </div>
        </div>

        <?php if ($order['order_livreur_id']): 
            $livreur = get_livreur_by_id($order['order_livreur_id']);
            if ($livreur): ?>
            <div style="margin-bottom: 25px; padding: 15px; background: #f1f5f9; border-radius: 12px; border: 1px solid var(--gray-border);">
                <div style="font-size: 10px; text-transform: uppercase; font-weight: 800; color: #64748b; margin-bottom: 5px;">Livreur Assigné:</div>
                <div style="font-weight: 800; font-size: 15px; display: flex; align-items: center; gap: 10px;">
                    <i class="bi bi-truck"></i> <?= htmlspecialchars($livreur['livreur_name']) ?> 
                    <?php if ($livreur['livreur_phone']): ?>
                        <span style="font-weight: 600; opacity: 0.7;">(<?= htmlspecialchars($livreur['livreur_phone']) ?>)</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; endif; ?>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;">Qté</th>
                    <th>Désignation / المنتج</th>
                    <th style="text-align: right;">Prix Unitaire</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td class="qty-cell"><?= (float)$item['order_item_quantity'] ?>x</td>
                        <td>
                            <?= htmlspecialchars($item['order_item_name_fr'] ?? '') ?>
                            <div style="font-size: 10px; opacity: 0.7; font-weight: 400;"><?= htmlspecialchars($item['order_item_name_ar'] ?? '') ?></div>
                        </td>
                        <td style="text-align: right;"><?= format_price($item['order_item_unit_price']) ?></td>
                        <td style="text-align: right;"><?= format_price($item['order_item_subtotal']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals-wrapper">
            <div class="totals-table">
                <div class="total-row">
                    <span>Sous-total</span>
                    <span><?= format_price($order['order_subtotal']) ?></span>
                </div>
                <div class="total-row">
                    <span>Livraison (Safi)</span>
                    <span><?= format_price($order['order_delivery_fee']) ?></span>
                </div>
                <div class="total-row grand-total">
                    <span>TOTAL</span>
                    <span style="color: var(--princeton-orange);"><?= format_price($order['order_total']) ?></span>
                </div>
            </div>
        </div>
        
        <?php if ($order['order_notes']): ?>
            <div style="margin-top: 20px; background: #fff9f2; padding: 15px; border-radius: 12px; border: 1px solid #ffe8d1; page-break-inside: avoid;">
                <h3 style="margin: 0 0 8px 0; font-size: 14px; text-transform: uppercase; color: #e67700f4; font-weight: 800;">Notes de livraison:</h3>
                <p style="margin: 0; font-size: 13px; font-weight: 600; line-height: 1.4; color: #717171;"><?= nl2br(htmlspecialchars($order['order_notes'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="receipt-footer">
            <p>Merci pour votre confiance ! / شكرا لثقتكم</p>
            <p>Paiement à la livraison • Matjar El Kotobia • Safi, Maroc</p>
            <p style="margin-top: 8px; font-style: italic; opacity: 0.4;">Document généré par le système de gestion Matjar El Kotobia</p>
        </div>
    </div>

    <div class="action-bar">
        <a href="order_detail.php?id=<?= $order_id ?>" class="print-btn" style="background: white; color: var(--carbon-black); border: 1px solid var(--gray-border);">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
        
        <button class="print-btn" style="background: #25D366;" onclick="shareAsImage('<?= $customer_wa_url ?>', this)">
            <i class="bi bi-whatsapp"></i> Envoyer au Client
        </button>

        <?php if ($livreur && $livreur['livreur_phone']): ?>
        <button class="print-btn" style="background: #128C7E;" onclick="shareAsImage('<?= $livreur_wa_url ?>', this)">
            <i class="bi bi-truck"></i> Envoyer au Livreur
        </button>
        <?php endif; ?>

        <button class="print-btn" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimer
        </button>
    </div>

    <script>
        async function shareAsImage(targetUrl, btn) {
            const container = document.querySelector('.receipt-container');
            const originalBtnText = btn.innerHTML;
            
            btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Copie en cours...';
            btn.disabled = true;

            try {
                const canvas = await html2canvas(container, {
                    scale: 3, // Very high quality for zoom
                    useCORS: true,
                    backgroundColor: '#ffffff'
                });

                canvas.toBlob(async (blob) => {
                    try {
                        const data = [new ClipboardItem({ [blob.type]: blob })];
                        await navigator.clipboard.write(data);
                        
                        btn.innerHTML = '<i class="bi bi-check2-all"></i> Copié ! Redirection...';
                        
                        // Short delay to let user see "Copié !"
                        setTimeout(() => {
                            btn.innerHTML = originalBtnText;
                            btn.disabled = false;
                            
                            // Redirect to WhatsApp
                            if (targetUrl && targetUrl !== '#') {
                                window.open(targetUrl, '_blank');
                            }
                        }, 1500);

                    } catch (clipboardError) {
                        console.error('Clipboard error:', clipboardError);
                        // Fallback: Download if clipboard fails (e.g. non-HTTPS)
                        const link = document.createElement('a');
                        link.download = "Bon_<?= $order['order_number'] ?>.png";
                        link.href = canvas.toDataURL('image/png');
                        link.click();
                        alert("L'image a été téléchargée (impossible de copier au presse-papier).");
                        
                        btn.innerHTML = originalBtnText;
                        btn.disabled = false;
                        if (targetUrl && targetUrl !== '#') {
                            window.open(targetUrl, '_blank');
                        }
                    }
                }, 'image/png');

            } catch (error) {
                console.error('Error generating image:', error);
                alert('Erreur lors de la génération de l\'image.');
                btn.innerHTML = originalBtnText;
                btn.disabled = false;
            }
        }
    </script>

</body>
</html>
