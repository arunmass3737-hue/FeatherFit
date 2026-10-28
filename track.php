<?php
/**
 * FEATHER FITS — PUBLIC ORDER TRACKING PORTAL
 * Accessible with or without login.
 * Secure phone/email verification protects customer privacy.
 */
require_once __DIR__ . '/data/products.php';

$pageTitle = "Track Your Order Live — Feather Fits Luxury Apparel";
$pageDescription = "Live real-time courier tracking for your Feather Fits graphic tees and streetwear orders via Blue Dart, Delhivery, DTDC, and India Post.";
$currentPage = 'track';
$currentDept = 'all';

$prefillOrder = htmlspecialchars($_GET['order'] ?? $_GET['id'] ?? '');
$prefillPhone = htmlspecialchars($_GET['phone'] ?? $_GET['email'] ?? '');

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Breadcrumbs -->
  <nav class="breadcrumbs-container" aria-label="Breadcrumb">
    <div class="breadcrumbs-wrap">
      <a href="index.php">Home</a>
      <span>/</span>
      <span style="color: var(--color-primary); font-weight: 700;">Live Order Tracking</span>
    </div>
  </nav>

  <!-- Main Tracking Container -->
  <main class="track-page-main" style="min-height: 75vh; padding: 2rem 1.25rem 4rem 1.25rem; background: #FDF7FB;">
    <div style="max-width: 820px; margin: 0 auto;">

      <!-- Header Banner -->
      <div style="text-align: center; margin-bottom: 2rem;">
        <span style="display: inline-block; padding: 0.25rem 0.85rem; border-radius: 999px; background: #FDF2F4; color: #E0245E; font-size: 0.78rem; font-weight: 800; text-transform: uppercase; margin-bottom: 0.6rem; letter-spacing: 0.05em;">
          REAL-TIME DISPATCH RADAR
        </span>
        <h1 style="font-family: 'Outfit', sans-serif; font-size: 2.2rem; font-weight: 900; color: #3B0F4A; margin-bottom: 0.5rem; letter-spacing: -0.01em;">
          Track Your Shipment
        </h1>
        <p style="font-size: 0.95rem; color: #724E79; max-width: 540px; margin: 0 auto;">
          Enter your Order ID along with your registered mobile phone or email to view real-time carrier status, live milestones, and estimated delivery.
        </p>
      </div>

      <!-- Tracking Lookup Form Card -->
      <div style="background: #FFFFFF; border: 1.5px solid #F0D7E7; border-radius: var(--radius-lg); padding: 1.75rem 2rem; box-shadow: 0 10px 30px rgba(59, 15, 74, 0.06); margin-bottom: 2rem;">
        <form id="public-track-form" onsubmit="handlePublicTrackSubmit(event)" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: flex-end;">
          <div>
            <label for="track-order-id" style="display: block; font-size: 0.8rem; font-weight: 800; color: #3B0F4A; text-transform: uppercase; margin-bottom: 0.4rem;">
              Order ID
            </label>
            <input 
              type="text" 
              id="track-order-id" 
              class="form-input" 
              placeholder="e.g. FF-90214" 
              value="<?php echo $prefillOrder; ?>" 
              required 
              style="height: 46px; width: 100%; text-transform: uppercase; font-weight: 700;"
            />
          </div>

          <div>
            <label for="track-phone-email" style="display: block; font-size: 0.8rem; font-weight: 800; color: #3B0F4A; text-transform: uppercase; margin-bottom: 0.4rem;">
              Phone (10 digits) or Email
            </label>
            <input 
              type="text" 
              id="track-phone-email" 
              class="form-input" 
              placeholder="e.g. 9876543210 or email" 
              value="<?php echo $prefillPhone; ?>" 
              required 
              style="height: 46px; width: 100%;"
            />
          </div>

          <button type="submit" id="track-submit-btn" class="btn-cta-crimson" style="height: 46px; padding: 0 1.75rem; font-size: 0.92rem; font-weight: 800; white-space: nowrap;">
            <span>Track Parcel →</span>
          </button>
        </form>

        <div id="track-error-alert" style="display: none; background: #FDF2F4; border: 1px solid #F87171; color: #DC2626; padding: 0.75rem 1rem; border-radius: var(--radius-sm); font-size: 0.85rem; font-weight: 700; margin-top: 1.25rem;"></div>
      </div>

      <!-- Dynamic Live Result Section -->
      <div id="track-result-container" style="display: none;"></div>

      <!-- FAQ & WhatsApp Support Card -->
      <div style="background: #FFFFFF; border: 1px solid #F0D7E7; border-radius: var(--radius-lg); padding: 1.75rem 2rem; margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
          <div>
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #3B0F4A; margin-bottom: 0.35rem;">
              Need help with your delivery?
            </h3>
            <p style="font-size: 0.85rem; color: #724E79; margin: 0;">
              Our dedicated concierge is available on WhatsApp daily from 9:00 AM to 9:00 PM IST.
            </p>
          </div>
          <a id="whatsapp-support-btn" href="https://wa.me/919876543210?text=Hi%20Feather%20Fits%20Support,%20I%20need%20help%20with%20my%20shipment" target="_blank" class="btn-primary" style="background: #25D366; color: #fff; height: 42px; padding: 0 1.35rem; font-size: 0.85rem; font-weight: 800; border: none; text-decoration: none;">
            <span>💬 Chat on WhatsApp</span>
          </a>
        </div>
      </div>

    </div>
  </main>

  <script>
    function handlePublicTrackSubmit(e) {
      if (e) e.preventDefault();
      const orderId = document.getElementById('track-order-id').value.trim();
      const phoneOrEmail = document.getElementById('track-phone-email').value.trim();
      const btn = document.getElementById('track-submit-btn');
      const errBox = document.getElementById('track-error-alert');
      const container = document.getElementById('track-result-container');

      if (!orderId || !phoneOrEmail) return;

      btn.disabled = true;
      btn.innerHTML = '<span>Scanning Radar...</span>';
      errBox.style.display = 'none';

      fetch(`api/shipping.php?action=track_public&orderId=${encodeURIComponent(orderId)}&phone=${encodeURIComponent(phoneOrEmail)}`)
        .then(res => res.json())
        .then(data => {
          btn.disabled = false;
          btn.innerHTML = '<span>Track Parcel →</span>';

          if (data.success && data.order) {
            renderPublicTrackingResult(data.order);
            container.style.display = 'block';
            container.scrollIntoView({ behavior: 'smooth', block: 'start' });

            // Update WhatsApp support button with order ID
            const waBtn = document.getElementById('whatsapp-support-btn');
            if (waBtn) {
              const waText = encodeURIComponent(`Hi Feather Fits Support, I need an update regarding my order #${data.order.id}.`);
              waBtn.href = `https://wa.me/919876543210?text=${waText}`;
            }
          } else {
            container.style.display = 'none';
            errBox.textContent = data.error || 'Order lookup failed.';
            errBox.style.display = 'block';
          }
        })
        .catch(err => {
          btn.disabled = false;
          btn.innerHTML = '<span>Track Parcel →</span>';
          errBox.textContent = 'Connection error. Please try again.';
          errBox.style.display = 'block';
        });
    }

    function renderPublicTrackingResult(order) {
      const container = document.getElementById('track-result-container');
      const status = (order.status || '').toLowerCase();

      // Stepper step states
      const steps = [
        { label: 'Order Placed', active: true, done: true },
        { label: 'Packed', active: order.progress >= 40, done: order.progress >= 40 },
        { label: 'Shipped', active: order.progress >= 65, done: order.progress >= 65 },
        { label: 'Out for Delivery', active: order.progress >= 85, done: order.progress >= 85 },
        { label: 'Delivered', active: order.progress === 100, done: order.progress === 100 }
      ];

      container.innerHTML = `
        <div style="background: #FFFFFF; border: 1.5px solid #F0D7E7; border-radius: var(--radius-lg); padding: 2rem; box-shadow: 0 16px 40px rgba(59, 15, 74, 0.08);">
          
          <!-- Top Status Strip -->
          <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; border-bottom: 1px solid #F0D7E7; padding-bottom: 1.25rem; margin-bottom: 1.75rem;">
            <div>
              <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #724E79;">PARCEL STATUS</span>
              <div style="display: flex; align-items: center; gap: 0.6rem; margin-top: 0.2rem;">
                <h2 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 900; color: #3B0F4A; margin: 0;">
                  ${order.status}
                </h2>
                <span style="padding: 0.2rem 0.65rem; border-radius: 999px; background: ${order.status === 'Delivered' ? '#ECFDF5' : '#FDF2F4'}; color: ${order.status === 'Delivered' ? '#059669' : '#E0245E'}; font-size: 0.75rem; font-weight: 800;">
                  ${order.status === 'Delivered' ? 'Completed' : 'In Transit'}
                </span>
              </div>
            </div>

            <div style="text-align: right;">
              <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #724E79;">ESTIMATED DELIVERY</span>
              <div style="font-family: 'Outfit', sans-serif; font-size: 1.2rem; font-weight: 800; color: #6A1B6E;">
                ${order.estimatedDelivery || 'In 2 - 3 Days'}
              </div>
            </div>
          </div>

          <!-- Stepper Visual Progress Bar -->
          <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; position: relative; margin-bottom: 0.75rem;">
              <!-- Progress Background Line -->
              <div style="position: absolute; top: 14px; left: 20px; right: 20px; height: 4px; background: #FAF0F6; z-index: 1;">
                <div style="height: 100%; width: ${Math.min(100, (order.progress - 10) * 1.15)}%; background: linear-gradient(90deg, #6A1B6E, #E0245E); border-radius: 4px; transition: width 0.6s ease;"></div>
              </div>

              ${steps.map((st, idx) => `
                <div style="position: relative; z-index: 2; display: flex; flex-direction: column; align-items: center; width: 70px;">
                  <div style="width: 30px; height: 30px; border-radius: 50%; background: ${st.done ? '#E0245E' : '#FAF0F6'}; color: ${st.done ? '#fff' : '#A686AD'}; border: 2px solid ${st.done ? '#E0245E' : '#F0D7E7'}; display: flex; align-items: center; justify-content: center; font-size: 0.78rem; font-weight: 800; box-shadow: ${st.done ? '0 2px 8px rgba(224, 36, 94, 0.35)' : 'none'};">
                    ${st.done ? '✓' : (idx + 1)}
                  </div>
                  <span style="font-size: 0.72rem; font-weight: 700; color: ${st.done ? '#3B0F4A' : '#A686AD'}; text-align: center; margin-top: 0.4rem; line-height: 1.2;">
                    ${st.label}
                  </span>
                </div>
              `).join('')}
            </div>
          </div>

          <!-- Courier & Tracking Details Card -->
          <div style="background: #FDF7FB; border: 1.5px solid #F0D7E7; border-radius: var(--radius-md); padding: 1.25rem 1.5rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.25rem;">
            <div>
              <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; color: #724E79;">COURIER PARTNER</span>
              <div style="font-size: 1.05rem; font-weight: 800; color: #3B0F4A; margin-top: 0.15rem;">
                ${order.carrier || 'Blue Dart Express'}
              </div>
              <div style="font-size: 0.85rem; color: #724E79; margin-top: 0.2rem;">
                AWB Tracking No: <strong style="color: #3B0F4A; font-family: monospace; letter-spacing: 0.05em;">${order.trackingNumber || 'Pending'}</strong>
              </div>
            </div>

            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
              ${order.trackingNumber ? `
                <button type="button" class="btn-secondary" style="height: 38px; font-size: 0.8rem;" onclick="copyTrackingCode('${order.trackingNumber}')">
                  📋 Copy AWB
                </button>
              ` : ''}

              ${order.trackingUrl ? `
                <a href="${order.trackingUrl}" target="_blank" rel="noopener noreferrer" class="btn-primary" style="height: 38px; font-size: 0.8rem; background: #6A1B6E; text-decoration: none;">
                  🚀 Carrier Tracking Page &rarr;
                </a>
              ` : ''}
            </div>
          </div>

          <!-- Destination Masked Details -->
          <div style="background: #FAF0F6; border-radius: var(--radius-sm); padding: 0.85rem 1.25rem; margin-bottom: 2rem; display: flex; justify-content: space-between; font-size: 0.85rem; color: #3B0F4A; flex-wrap: wrap; gap: 0.75rem;">
            <div>
              <strong>Delivering To:</strong> ${order.destination?.recipient || 'Customer'}, ${order.destination?.city || 'India'}
            </div>
            <div>
              <strong>Verified Mobile:</strong> ${order.destination?.phone || '******'}
            </div>
          </div>

          <!-- Activity Timeline History -->
          <div style="margin-bottom: 2rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: #3B0F4A; margin-bottom: 1rem;">
              Shipment Activity Timeline
            </h3>

            <div style="border-left: 2px solid #F0D7E7; margin-left: 0.75rem; padding-left: 1.25rem; display: flex; flex-direction: column; gap: 1.25rem;">
              ${(order.timeline && order.timeline.length > 0) ? order.timeline.map((item, idx) => `
                <div style="position: relative;">
                  <div style="position: absolute; left: -1.68rem; top: 2px; width: 12px; height: 12px; border-radius: 50%; background: ${idx === order.timeline.length - 1 ? '#E0245E' : '#B5258F'}; border: 2px solid #FFFFFF;"></div>
                  <div style="font-size: 0.88rem; font-weight: 800; color: #3B0F4A;">${item.status}</div>
                  <div style="font-size: 0.8rem; color: #724E79; margin: 0.15rem 0;">${item.note || ''}</div>
                  <div style="font-size: 0.75rem; color: #A686AD;">${item.time} &bull; ${item.updatedBy || 'System'}</div>
                </div>
              `).reverse().join('') : '<p style="font-size: 0.85rem; color: #724E79;">No tracking checkpoints recorded yet.</p>'}
            </div>
          </div>

          <!-- Items in Shipment Preview -->
          <div>
            <h3 style="font-size: 1.05rem; font-weight: 800; color: #3B0F4A; margin-bottom: 0.85rem;">
              Apparel Items in this Parcel
            </h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
              ${order.items.map(it => `
                <div style="display: flex; align-items: center; gap: 1rem; padding: 0.6rem; border: 1px solid #F0D7E7; border-radius: var(--radius-sm); background: #FFFFFF;">
                  <img src="${it.image || 'assets/images/anime-tee-model-front.jpg'}" style="width: 48px; height: 48px; border-radius: var(--radius-xs); object-fit: cover;" />
                  <div>
                    <div style="font-size: 0.88rem; font-weight: 700; color: #3B0F4A;">${it.name}</div>
                    <div style="font-size: 0.78rem; color: #724E79;">Size: ${it.size} &bull; Quantity: ${it.qty}</div>
                  </div>
                </div>
              `).join('')}
            </div>
          </div>

        </div>
      `;
    }

    function copyTrackingCode(code) {
      if (!code) return;
      navigator.clipboard.writeText(code).then(() => {
        if (window.FeatherApp && window.FeatherApp.showToast) {
          window.FeatherApp.showToast('AWB Tracking code copied to clipboard!');
        } else {
          alert('Copied AWB: ' + code);
        }
      });
    }

    // Auto-trigger if order & phone query params exist
    document.addEventListener('DOMContentLoaded', () => {
      const orderVal = document.getElementById('track-order-id').value.trim();
      const phoneVal = document.getElementById('track-phone-email').value.trim();
      if (orderVal && phoneVal) {
        handlePublicTrackSubmit();
      }
    });
  </script>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/modals.php';
?>
</body>
</html>
