<style>
/* User Menu Styles */
.user-menu-container {
    position: relative;
}

.user-icon-btn {
    color: var(--dark-color) !important;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none !important;
    background: none !important;
    padding: 8px 12px !important;
    border-radius: 8px;
}

.user-icon-btn:hover {
    color: var(--primary-color) !important;
    background: rgba(255, 107, 53, 0.1) !important;
}

.user-name {
    font-weight: 600;
}

.user-popup {
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    min-width: 250px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px);
    transition: all 0.3s ease;
    z-index: 1000;
    margin-top: 10px;
}

.user-popup.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.popup-header {
    padding: 15px 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    color: var(--dark-color);
}

.popup-header i {
    color: var(--primary-color);
    font-size: 1.2rem;
}

.popup-actions {
    padding: 10px 0;
}

.popup-action {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: var(--dark-color);
    text-decoration: none;
    transition: all 0.3s ease;
    font-weight: 500;
}

.popup-action:hover {
    background: var(--light-bg);
    color: var(--primary-color);
}

.popup-action.logout:hover {
    background: #fee;
    color: #dc3545;
}

.popup-action i {
    width: 20px;
    text-align: center;
}
</style>
