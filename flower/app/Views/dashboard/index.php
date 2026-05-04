<?= $this->include('templates/header') ?>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<h1 class="h4 mb-4 text-secondary">Dashboard</h1>

<!-- Quick action cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-cart-plus text-primary" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="card-title">Order Flowers</h5>
                <p class="card-text text-muted small">Create and manage flower orders</p>
                <a href="#" class="btn btn-sm btn-outline-primary">Go to Orders</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-box-seam text-success" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="card-title">Flower Inventory</h5>
                <p class="card-text text-muted small">Track stock and availability</p>
                <a href="#" class="btn btn-sm btn-outline-success">View Inventory</a>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-body text-center p-4">
                <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                    <i class="bi bi-flower2 text-danger" style="font-size: 1.75rem;"></i>
                </div>
                <h5 class="card-title">Flower Types</h5>
                <p class="card-text text-muted small">Manage flower categories</p>
                <a href="#" class="btn btn-sm btn-outline-danger">View Types</a>
            </div>
        </div>
    </div>
</div>

<!-- Flower Types section -->
<h2 class="h5 mb-3 text-secondary">Flower Types</h2>
<div class="row g-4">
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #dc3545 !important;">
            <div class="card-body d-flex align-items-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background: #ffe4ec;">
                    <i class="bi bi-flower1 text-danger" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Roses</h5>
                    <p class="card-text text-muted small mb-0">Classic roses</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ffc107 !important;">
            <div class="card-body d-flex align-items-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background: #fff8e1;">
                    <i class="bi bi-flower1 text-warning" style="font-size: 1.5rem;"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Daisies</h5>
                    <p class="card-text text-muted small mb-0">Fresh daisies</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 4px solid #ff69b4 !important;">
            <div class="card-body d-flex align-items-center p-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center me-3" style="width: 56px; height: 56px; background: #ffe4ec;">
                    <i class="bi bi-flower1" style="font-size: 1.5rem; color: #ff69b4;"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0">Tulips</h5>
                    <p class="card-text text-muted small mb-0">Elegant tulips</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->include('templates/footer') ?>
