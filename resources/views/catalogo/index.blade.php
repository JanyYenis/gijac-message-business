@extends('layouts.index')

@section('css')
    <link rel="stylesheet" href="{{ mix('css/catalogo.css') }}">
@endsection

@section('content')
    <!-- Content -->
    <div class="p-4 p-lg-5">
        <div
            class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <h1 class="h4 fw-bold m-0" style="letter-spacing:-.02em">Catálogo de WhatsApp Business</h1>
                <p class="text-muted mb-0 small mt-1">Gestiona productos, precios, stock y visibilidad para tu tienda WABA.
                </p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-soft" id="demoToggle"><i class="bi bi-database me-1"></i> <span>Modo demo</span></button>
                <button class="btn-gj" id="addProductBtn"><i class="bi bi-plus-lg me-1"></i> Nuevo producto</button>
            </div>
        </div>

        <!-- KPIs -->
        <div class="row g-3 mb-4" id="kpiRow">
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="ic ic-teal"><i class="bi bi-box-seam"></i></div>
                    <div>
                        <h3 id="kpiTotal">0</h3>
                        <small>Productos totales</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="ic ic-green"><i class="bi bi-check-circle"></i></div>
                    <div>
                        <h3 id="kpiActive">0</h3>
                        <small>Activos</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="ic ic-amber"><i class="bi bi-exclamation-triangle"></i></div>
                    <div>
                        <h3 id="kpiLow">0</h3>
                        <small>Stock bajo</small>
                    </div>
                </div>
            </div>
            <div class="col-6 col-xl-3">
                <div class="kpi">
                    <div class="ic ic-blue"><i class="bi bi-currency-dollar"></i></div>
                    <div>
                        <h3 id="kpiAvg">$0</h3>
                        <small>Precio promedio</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar mb-4">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label small text-uppercase mb-1" style="color:var(--gj-900)">Categoría</label>
                    <select class="form-select form-select-gj" id="filterCategory">
                        <option value="">Todas las categorías</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label small text-uppercase mb-1" style="color:var(--gj-900)">Disponibilidad</label>
                    <select class="form-select form-select-gj" id="filterAvailability">
                        <option value="">Todos</option>
                        <option value="in stock">En stock</option>
                        <option value="out of stock">Agotado</option>
                        <option value="preorder">Preventa</option>
                        <option value="not available">No disponible</option>
                    </select>
                </div>
                <div class="col-12 col-md-4 col-lg-3">
                    <label class="form-label small text-uppercase mb-1" style="color:var(--gj-900)">Ordenar</label>
                    <select class="form-select form-select-gj" id="sortBy">
                        <option value="newest">Más recientes</option>
                        <option value="price_asc">Precio: menor a mayor</option>
                        <option value="price_desc">Precio: mayor a menor</option>
                        <option value="name_asc">Nombre A-Z</option>
                    </select>
                </div>
                <div class="col-12 col-lg-3 d-flex justify-content-lg-end">
                    <div class="text-muted small" id="resultsCount">Cargando…</div>
                </div>
            </div>
        </div>

        <!-- Grid -->
        <div class="row g-4" id="productsGrid">
            <!-- Skeletons se inyectan desde JS -->
        </div>

        <!-- Empty state -->
        <div class="state-box d-none" id="emptyState">
            <div class="ic ic-teal"><i class="bi bi-inbox"></i></div>
            <h5 class="fw-bold">No se encontraron productos</h5>
            <p class="text-muted mb-0">Ajusta los filtros o crea un producto nuevo para empezar.</p>
        </div>

        <!-- Error state -->
        <div class="state-box d-none" id="errorState">
            <div class="ic ic-amber" style="color:#B36B00; background:#FFF3E0"><i class="bi bi-exclamation-octagon"></i>
            </div>
            <h5 class="fw-bold" id="errorTitle">Error al cargar el catálogo</h5>
            <p class="text-muted mb-3" id="errorMsg">Revisa la consola para más detalles.</p>
            <button class="btn-gj" id="retryBtn"><i class="bi bi-arrow-counterclockwise me-1"></i> Reintentar</button>
        </div>

        <!-- Pagination / Load more -->
        <div class="d-flex justify-content-center mt-4">
            <button class="btn-soft d-none" id="loadMoreBtn">Cargar más</button>
            <nav class="d-none" id="paginationNav" aria-label="Paginación"></nav>
        </div>
    </div>

    <!-- Toast stack -->
    <div class="toast-stack" id="toastStack"></div>
@endsection

@section('modal')
    <!-- Product modal: add/edit -->
    <div class="modal fade modal-gj" id="productModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="productModalLabel">Nuevo producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <form id="productForm" novalidate>
                    <div class="modal-body">
                        <input type="hidden" id="productId">
                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <label class="form-label">Nombre del producto *</label>
                                <input type="text" class="form-control form-control-gj" id="pName" required
                                    maxlength="150">
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">SKU / Referencia</label>
                                <input type="text" class="form-control form-control-gj" id="pSku"
                                    maxlength="50">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <textarea class="form-control form-control-gj" id="pDesc" rows="3" maxlength="5000"></textarea>
                                <div class="help mt-1"><span id="pDescCount">0</span>/5000</div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Categoría</label>
                                <input type="text" class="form-control form-control-gj" id="pCategory"
                                    list="categoryOptions" maxlength="100">
                                <datalist id="categoryOptions">
                                    <option value="Electrónica">
                                    <option value="Hogar">
                                    <option value="Ropa">
                                    <option value="Alimentos">
                                    <option value="Belleza">
                                    <option value="Deportes">
                                    <option value="Juguetes">
                                    <option value="Servicios">
                                </datalist>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Precio *</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0"
                                        style="background:#fff; color:var(--gj-900); font-weight:700">$</span>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control form-control-gj" id="pPrice" required>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Precio oferta</label>
                                <div class="input-group">
                                    <span class="input-group-text border-0"
                                        style="background:#fff; color:var(--gj-900); font-weight:700">$</span>
                                    <input type="number" step="0.01" min="0"
                                        class="form-control form-control-gj" id="pSalePrice">
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Moneda</label>
                                <select class="form-select form-select-gj" id="pCurrency">
                                    <option value="USD">USD</option>
                                    <option value="EUR">EUR</option>
                                    <option value="COP" selected>COP</option>
                                    <option value="MXN">MXN</option>
                                    <option value="BRL">BRL</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Disponibilidad</label>
                                <select class="form-select form-select-gj" id="pAvailability">
                                    <option value="in stock">En stock</option>
                                    <option value="out of stock">Agotado</option>
                                    <option value="preorder">Preventa</option>
                                    <option value="not available">No disponible</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" min="0" class="form-control form-control-gj"
                                    id="pQuantity">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Condición</label>
                                <select class="form-select form-select-gj" id="pCondition">
                                    <option value="new">Nuevo</option>
                                    <option value="refurbished">Reacondicionado</option>
                                    <option value="used">Usado</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Imagen del producto (URL)</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text border-0" style="background:#fff"><i
                                            class="bi bi-link-45deg"></i></span>
                                    <input type="url" class="form-control form-control-gj" id="pImageUrl"
                                        placeholder="https://…">
                                </div>
                                <div class="img-preview" id="imgPreview" style="height:180px">
                                    <div class="text-center"><i class="bi bi-image fs-2 mb-2 d-block"></i> Vista previa de
                                        imagen</div>
                                </div>
                                <div class="help mt-1">La API requiere imágenes con formato JPG/PNG y tamaño recomendado
                                    500x500 px.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-soft" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn-gj" id="saveProductBtn">Guardar producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete confirmation modal -->
    <div class="modal fade modal-gj" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Eliminar producto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">¿Seguro que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-soft" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-gj" id="confirmDeleteBtn"
                        style="background:linear-gradient(135deg,#C0392B,#e74c3c); box-shadow:0 8px 20px rgba(192,57,43,.28)">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ mix('/js/catalogo/principal.js') }}"></script>
@endsection
