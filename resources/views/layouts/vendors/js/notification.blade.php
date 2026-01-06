<audio id="notifSound" preload="auto">
    <source src="{{ asset('assets/sounds/notification.wav') }}" type="audio/mpeg">
</audio>

<div id="undoToast" class="undo-toast">
    <span>Notification deleted</span>
    <button id="undoBtn">UNDO</button>
</div>


<style>
    .txn-badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 10px;
    font-weight: 600;
    margin-bottom: 4px;
    display: inline-block;
}

.txn-badge.udhar {
    background: #fee2e2;
    color: #b91c1c;
}

.txn-badge.jama {
    background: #dcfce7;
    color: #166534;
}

.notif-svg {
    display: inline-flex;
    align-items: center;
    justify-content: center;
}


.notif-svg{width:22px;height:22px;margin-right:10px}
.svg-icon{width:100%;height:100%;fill:none;stroke-width:1.8}
.scheme {
    stroke: #0ea5e9; /* blue-ish */
}

.bill{stroke:#2563eb}
.gold{stroke:#d4af37}
.silver{stroke:#9ca3af}
.cash{stroke:#16a34a}
.scheme{stroke:#f97316} /* orange – scheme */

.box{stroke:#7c3aed}

@keyframes spinSoft{to{transform:rotate(360deg)}}
.spin-soft{animation:spinSoft 6s linear infinite}

@keyframes shimmer{0%,100%{opacity:.6}50%{opacity:1}}
.shimmer{animation:shimmer 2s infinite}

@keyframes bounceSoft{0%,100%{transform:translateY(0)}50%{transform:translateY(-4px)}}
.bounce-soft{animation:bounceSoft 1.4s infinite}

@keyframes pulseRed{
  0%{box-shadow:0 0 0 0 rgba(239,68,68,.5)}
  70%{box-shadow:0 0 0 8px rgba(239,68,68,0)}
}
.pulse-red{animation:pulseRed 2s infinite;border-radius:50%}

@keyframes pulseGreen{
  0%{box-shadow:0 0 0 0 rgba(34,197,94,.5)}
  70%{box-shadow:0 0 0 8px rgba(34,197,94,0)}
}
.pulse-green{animation:pulseGreen 2s infinite;border-radius:50%}

@keyframes billGlow{50%{filter:drop-shadow(0 0 6px #2563eb)}}
.bill-anim{animation:billGlow 2.5s infinite}


</style>

<script>

    function toggleNotifActions() {
    if (currentTab === 'unread') {
        $('#markAllRead').show();
        $('#clearNotif').hide();
    } else {
        $('#markAllRead').hide();
        $('#clearNotif').show();
    }
}

let currentTab = 'unread'; // default tab
// $('#markAllRead').show(); // default unread hai


/* ---------------- TIME AGO ---------------- */
function timeAgo(dateString) {
    const seconds = Math.floor((new Date() - new Date(dateString)) / 1000);
    const intervals = {
        year: 31536000,
        month: 2592000,
        day: 86400,
        hour: 3600,
        minute: 60
    };
    for (let key in intervals) {
        const value = Math.floor(seconds / intervals[key]);
        if (value >= 1) {
            return value + ' ' + key + (value > 1 ? 's' : '') + ' ago';
        }
    }
    return 'just now';
}


/* ================= SVG ICON FUNCTIONS ================= */

function svgBill() {
    return `
    <svg viewBox="0 0 24 24" class="svg-icon bill" stroke="currentColor">
        <rect x="5" y="3" width="14" height="18" rx="2"/>
        <line x1="8" y1="7" x2="16" y2="7"/>
        <line x1="8" y1="11" x2="16" y2="11"/>
        <line x1="8" y1="15" x2="13" y2="15"/>
    </svg>`;
}


function svgBox() {
    return `
    <svg viewBox="0 0 24 24" class="svg-icon box" stroke="currentColor">
        <path d="M3 7l9-4 9 4-9 4-9-4z"/>
        <path d="M3 7v10l9 4 9-4V7"/>
    </svg>`;
}


function svgGold() {
    return `
    <svg viewBox="0 0 24 24" class="svg-icon gold" stroke="currentColor">
        <circle cx="12" cy="12" r="7"/>
        <circle cx="12" cy="12" r="4"/>
    </svg>`;
}


function svgSilver() {
    return `
    <svg viewBox="0 0 24 24" class="svg-icon silver" stroke="currentColor">
        <circle cx="12" cy="12" r="8"/>
        <circle cx="12" cy="12" r="5"/>
    </svg>`;
}


function svgCash() {
    return `
    <svg viewBox="0 0 24 24" class="svg-icon cash" stroke="currentColor">
        <rect x="3" y="6" width="18" height="12" rx="2"/>
        <circle cx="12" cy="12" r="3"/>
    </svg>`;
}


function svgScheme() {
    return `
    <svg viewBox="0 0 24 24" class="svg-icon scheme" fill="none" stroke="currentColor">
        <!-- outer card -->
        <rect x="3" y="4" width="18" height="16" rx="2"/>

        <!-- dollar sign -->
        <text 
            x="12" 
            y="16" 
            text-anchor="middle" 
            font-size="12" 
            font-weight="700" 
            fill="currentColor"
            font-family="Arial, sans-serif"
        >$</text>
    </svg>`;
}





/* ---------------- ICON ---------------- */
function notifIcon(n) {
    const type = (n.data.type || '').toLowerCase();

    let $wrap = $('<div/>', { class: 'notif-svg' });

    /* ================= BILL ================= */
    if (type === 'bill') {
        $wrap
            .addClass('bill-anim')
            .html(svgBill());
    }

    /* ================= STOCK ================= */
    else if (type === 'stock') {

        if (n.data.stock_type === 'gold') {
            $wrap.addClass('spin-soft').html(svgGold());
        }
        else if (n.data.stock_type === 'silver') {
            $wrap.addClass('shimmer').html(svgSilver());
        }
        else {
            $wrap.addClass('bounce-soft').html(svgBox());
        }
    }

    /* ================= UDHAR / JAMA ================= */
    else if (type === 'udhar') {
       

        if (n.data.item_type === 'cash') {
            $wrap.html(svgCash());
        }
        else if (n.data.item_type === 'gold') {
            $wrap.html(svgGold());
        }
        else if (n.data.item_type === 'silver') {
            $wrap.html(svgSilver());
        }

        if (n.data.txn_type === 'udhar') {
            $wrap.addClass('pulse-red');
        } else {
            $wrap.addClass('pulse-green');
        }
        
    }
    /* ================= SCHEME ENQUIRY ================= */
        else if (type === 'scheme_enquiry') {
        $wrap.addClass('bounce-soft').html(svgScheme());
        }



    return $wrap;
}

/* ---------------- OPEN / CLOSE ---------------- */
$('#openNotif').on('click', function () {
    $('#notifPanel, #notifOverlay').addClass('active');

    currentTab = 'unread';   // force default
    toggleNotifActions();    // 🔥 IMPORTANT

    loadNotifications();
});


$('#closeNotif, #notifOverlay').on('click', function () {
    $('#notifPanel, #notifOverlay').removeClass('active');
});
/**read/ *******************unread */

$(document).on('click', '.notif-tab', function () {
    $('.notif-tab').removeClass('active');
    $(this).addClass('active');

    currentTab = $(this).data('type'); // unread | read
     // 🔥 Clear All → sirf READ me
    if (currentTab === 'read') {
        $('#clearNotif').show();
    } else {
        $('#clearNotif').hide();
    }
      // 👇👇 YAHI ADD KAR
    if (currentTab === 'unread') {
        $('#markAllRead').show();
    } else {
        $('#markAllRead').hide();
    }

    loadNotifications();
});


/* ---------------- CLEAR ALL ---------------- */
$('#clearNotif').on('click', function () {
    if (!confirm('Clear all notifications?')) return;

    $.post("{{ route('notifications.clear') }}", {
        _token: "{{ csrf_token() }}"
    }, function () {
        $('.notif-body').html(`
            
            <div class="notif-empty">
                <i class="fa fa-bell-slash"></i><br>No notifications
            </div>
        `);
        $('#notifCount').hide();
        lastUnread = 0;
    });
});

/* ---------------- UNREAD COUNT + SOUND ---------------- */
let lastUnread = parseInt(localStorage.getItem('lastUnreadCount')) || 0;


function loadUnreadCount() {
    $.get("{{ route('notifications.unread') }}", function (data) {
        const $badge = $('#notifCount');

        if (data.count > 0) {
            $badge.text(data.count).show();

            // 🔥 PLAY SOUND ONLY IF COUNT INCREASED
            if (data.count > lastUnread) {
                document.getElementById('notifSound').play();
            }

            // save latest count
            lastUnread = data.count;
            localStorage.setItem('lastUnreadCount', data.count);

        } else {
            $badge.hide();
            lastUnread = 0;
            localStorage.setItem('lastUnreadCount', 0);
        }
    });
}


/* ---------------- LOAD NOTIFICATIONS ---------------- */
/* ---------------- LOAD NOTIFICATIONS ---------------- */
function loadNotifications() {
    $.get("{{ route('notifications') }}", function (list) {
        const $body = $('.notif-body');
        $body.empty();

        if (!list || !list.data || list.data.length === 0) {
            $body.html(`
                <div class="notif-empty">
                    <i class="fa fa-bell-slash"></i><br>No notifications yet
                </div>
            `);
            return;
        }

        const unreadCount = list.data.filter(n => n.read_at === null).length;

        if (unreadCount > 0) {
            $('#unreadBadge').text(unreadCount).show();
        } else {
            $('#unreadBadge').hide();
        }

        const filtered = list.data.filter(n => {
            return currentTab === 'unread'
                ? n.read_at === null
                : n.read_at !== null;
        });

        if (filtered.length === 0) {
            $body.html(`
                <div class="notif-empty">
                    <i class="fa fa-bell"></i><br>
                    No ${currentTab} notifications
                </div>
            `);
            return;
        }

// 🔹 RENDER (FINAL)
filtered.forEach(n => {

    const isUnread = n.read_at === null;

    const $wrapper = $('<div/>', { class: 'notif-wrapper' });

    const $delete = $('<div/>', {
        class: 'notif-delete',
        html: '<i class="fa fa-trash"></i>'
    });

    const $item = $('<div/>', {
        class: 'notif-item ' + (isUnread ? 'unread' : 'read'),
        'data-id': n.id,
        'data-link': n.data.link
    });

    /* ---------- UDHAR / JAMA BADGE ---------- */
    let badgeHtml = '';
    if (n.data.txn_type === 'udhar') {
        badgeHtml = `<span class="txn-badge udhar">UDHAR</span>`;
    } else if (n.data.txn_type === 'jama') {
        badgeHtml = `<span class="txn-badge jama">JAMA</span>`;
    }

    /* ---------- AMOUNT / BALANCE TEXT ---------- */
    let amountText = '';
    let balanceText = n.data.balance ?? '';

    // STOCK
    if (n.data.item_count !== undefined && n.data.item_count !== null) {
        const count = Number(n.data.item_count);
        amountText = count === 1 ? '1 Item Added' : `${count} Items Added`;
        balanceText = '';
    }
    // GOLD
    else if (n.data.item_type === 'gold' && n.data.amount !== undefined) {
        amountText = `${n.data.amount} gm GOLD`;
    }
    // SILVER
    else if (n.data.item_type === 'silver' && n.data.amount !== undefined) {
        amountText = `${n.data.amount} gm SILVER`;
    }
    // CASH
    else if (n.data.amount !== undefined) {
        amountText = `₹${n.data.amount}`;
    }

    /* ---------- SCHEME ENQUIRY EXTRA INFO ---------- */
    let extraInfo = '';
    if (n.data.scheme || n.data.utr || n.data.amount) {
        extraInfo = `
            <br>
            <small>
                ${n.data.scheme ? `<b>Scheme:</b> ${n.data.scheme}<br>` : ''}
                ${n.data.amount ? `Amount: ₹${n.data.amount}<br>` : ''}
                ${n.data.utr ? `UTR: ${n.data.utr}` : ''}
            </small>
        `;
    }

    /* ---------- LEFT CONTENT ---------- */
    const $left = $('<div/>', { class: 'notif-left' })
        .append(`<div class="notif-title">${n.data.title}</div>`)
        .append(`
            <div class="notif-msg">
                ${n.data.message}
                ${extraInfo}
                <br>
                <small>
                    ${amountText ? amountText + '<br>' : ''}
                    ${balanceText ? 'Balance: ' + balanceText : ''}
                </small>
            </div>
        `)
        .append(`<div class="notif-time">${timeAgo(n.created_at)}</div>`);

    /* ---------- RIGHT ---------- */
    const $right = $('<div/>', { class: 'notif-right' })
        .append(badgeHtml)
        .append(
            isUnread
                ? '<span class="notif-badge new">NEW</span>'
                : '<span class="notif-badge read">READ</span>'
        );

    $item.append(notifIcon(n)).append($left).append($right);
    $wrapper.append($delete).append($item);
    $body.append($wrapper);
});


    });
}

//-------------mark read-----------//
$('#markAllRead').on('click', function () {
    if (!confirm('Mark all notifications as read?')) return;

    $.post("{{ route('notifications.read.all') }}", {
        _token: "{{ csrf_token() }}"
    }, function () {
        loadNotifications();
        loadUnreadCount();
    });
});




/* ---------------- SWIPE LOGIC ---------------- */
let startX = 0;
let endX = 0;

$(document).on('touchstart', '.notif-item', function (e) {
    startX = e.originalEvent.touches[0].clientX;
});

$(document).on('touchmove', '.notif-item', function (e) {
    endX = e.originalEvent.touches[0].clientX;
});

$(document).on('touchend', '.notif-item', function () {
    const diff = startX - endX;
    const $wrapper = $(this).closest('.notif-wrapper');

    if (diff > 60) {
        // 👉 swipe LEFT → show delete (same as before)
        $wrapper.removeClass('swiped-right').addClass('swiped');
    }
    else if (diff < -60) {
        // 👉 swipe RIGHT → RESET to original position
        // ❌ NO mark-read
        // ❌ NO API call

        $wrapper.removeClass('swiped');

        // smooth snap-back animation
        $wrapper.addClass('reset');

        setTimeout(() => {
            $wrapper.removeClass('reset');
        }, 250);
    }

    startX = endX = 0;
});


/* ---------------- DELETE ---------------- */
let deletedItem = null;
let deleteTimer = null;

/* ---------------- DELETE WITH UNDO ---------------- */
$(document).on('click', '.notif-delete', function (e) {
    e.stopPropagation();

    const $wrapper = $(this).closest('.notif-wrapper');
    const notifId = $wrapper.find('.notif-item').data('id');

    if (!notifId) return;

    // temporarily remove from UI
    deletedItem = {
        id: notifId,
        html: $wrapper.clone(true)
    };

    $wrapper.remove();
    showUndoToast();

    // actual delete after 5 sec
    deleteTimer = setTimeout(() => {
        $.post("{{ route('notifications.delete') }}", {
            _token: "{{ csrf_token() }}",
            id: notifId
        }, function () {
            loadUnreadCount();
        });

        deletedItem = null;
    }, 5000);
});

/* ---------------- UNDO CLICK ---------------- */
$('#undoBtn').on('click', function () {
    if (!deletedItem) return;

    clearTimeout(deleteTimer);
    $('.notif-body').prepend(deletedItem.html);
    hideUndoToast();
    deletedItem = null;
});

/* ---------------- TOAST HELPERS ---------------- */
function showUndoToast() {
    $('#undoToast').addClass('show');
}

function hideUndoToast() {
    $('#undoToast').removeClass('show');
}

/* ---------------- CLICK → BILL JUMP (ALWAYS) ---------------- */
$(document).on('click', '.notif-item', function () {

    const notifId = $(this).data('id');
    const link = $(this).data('link');

    // mark this notification as READ
    $.post("{{ route('notifications.read.single') }}", {
        _token: "{{ csrf_token() }}",
        id: notifId
    });

    if (link) window.location.href = link;
});

/* ---------------- INIT ---------------- */
loadUnreadCount();
</script>
