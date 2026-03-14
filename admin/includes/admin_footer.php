<?php
/**
 * FILE: admin/includes/admin_footer.php
 * PURPOSE: Admin panel footer and closing scripts.
 */
?>
        </div><!-- /view-section -->
    </main>

    <script>
        lucide.createIcons();

        /* Real-time New Order Notifications */
        let lastOrderId = 0;
        const checkInterval = 10000; // 10 seconds

        // Request permission for browser notifications
        if (Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission();
        }

        async function initLastOrderId() {
            try {
                const response = await fetch('<?= SITE_URL ?>/ajax/admin_check_new_orders.php');
                const data = await response.json();
                lastOrderId = data.latest_id;
            } catch (e) { console.error('Notification init error:', e); }
        }

        async function checkForNewOrders() {
            if (lastOrderId === 0) return;
            try {
                const response = await fetch(`<?= SITE_URL ?>/ajax/admin_check_new_orders.php?last_id=${lastOrderId}`);
                const data = await response.json();
                
                if (data.new_orders && data.new_orders.length > 0) {
                    data.new_orders.forEach(order => {
                        showOrderNotification(order);
                        showBrowserNotification(order); // Added for background alerts
                    });
                    lastOrderId = data.latest_id;
                    
                    // Play notification sound
                    const audio = new Audio('<?= SITE_URL ?>/assets/sounds/notification.mp3');
                    audio.play().catch(e => { console.warn('Could not play notification sound:', e); }); 
                }
            } catch (e) { console.error('Polling error:', e); }
        }

        function showOrderNotification(order) {
            const container = document.getElementById('hri-notifications-container');
            if (!container) return; // Guard clause

            const notif = document.createElement('div');
            notif.className = 'hri-notification';
            notif.innerHTML = `
                <div class="hri-notification__close" onclick="this.parentElement.remove()">
                    <i data-lucide="x" size="14"></i>
                </div>
                <div class="hri-notification__icon">
                    <i data-lucide="shopping-cart" size="20"></i>
                </div>
                <div>
                    <div class="hri-notification__title"><?= translate('new_order_notification') ?></div>
                    <div class="hri-notification__text">
                        #${order.order_number} - <b>${order.order_customer_name}</b><br>
                        Total: <b>${order.order_total} DH</b>
                    </div>
                    <a href="<?= SITE_URL ?>/admin/order_detail.php?id=${order.order_id}" class="hri-notification__btn">
                        <?= translate('view_order') ?> <i data-lucide="arrow-right" size="12"></i>
                    </a>
                </div>
            `;
            container.appendChild(notif);
            lucide.createIcons();

            // Auto-remove after 30 seconds
            setTimeout(() => { if (notif.parentElement) notif.remove(); }, 30000);
        }

        function showBrowserNotification(order) {
            if (Notification.permission === "granted") {
                const title = "<?= translate('new_order_notification') ?>";
                const options = {
                    body: `#${order.order_number} - ${order.order_customer_name} (${order.order_total} DH)`,
                    icon: '<?= SITE_URL ?>/assets/images/logo/logo.png'
                };
                const notification = new Notification(title, options);
                notification.onclick = function() {
                    window.focus();
                    window.location.href = `<?= SITE_URL ?>/admin/order_detail.php?id=${order.order_id}`;
                };
            }
        }

        initLastOrderId().then(() => {
            setInterval(checkForNewOrders, checkInterval);
        });
    </script>
</body>
</html>
