<div class="container mt-4">
    <!-- Dropdown for categories -->
    <div class="mb-4">
        <label for="categorySelect">Choose a category:</label>
        <select id="categorySelect" class="form-control" onchange="filterByCategory(this.value)">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="mb-4">
        <label for="searchInput">Search:</label>
        <input type="text" id="searchInput" class="form-control" oninput="searchServices()" placeholder="Search by service name, category or username">
    </div>

    <!-- Conteneur pour les résultats de recherche -->
    <div id="searchResults" class="row"></div>

    <?php foreach ($servicesGroupedByCategory as $categoryId => $categoryData): ?>
        <h2><?php echo htmlspecialchars($categoryData['category_name']); ?></h2>
        <div id="carousel-<?php echo $categoryId; ?>" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <?php foreach (array_chunk($categoryData['services'], 4) as $index => $serviceChunk): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row">
                            <?php foreach ($serviceChunk as $service): ?>
                                <div class="col-md-3">
                                    <div class="card service-card">
                                        <div class="card-body service-card-body">
                                            <h5 class="card-title"><?php echo htmlspecialchars($service->name); ?></h5>
                                            <p class="card-text"><?php echo htmlspecialchars($service->description); ?></p>
                                            <p class="card-text"><small class="text-muted">By <?php echo htmlspecialchars($service->username ?? ''); ?></small></p>
                                            <a href="#" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a class="carousel-control-prev" href="#carousel-<?php echo $categoryId; ?>" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-<?php echo $categoryId; ?>" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
        <hr>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function filterByCategory(categoryId) {
            var url = new URL(window.location.href);
            url.searchParams.set('category', categoryId);
            window.location.href = url.toString();
        }

        function searchServices() {
            const query = document.getElementById('searchInput').value;
            fetch('/seha/public/service/search?query=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    const searchResults = document.getElementById('searchResults');
                    searchResults.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(service => {
                            const serviceElement = document.createElement('div');
                            serviceElement.className = 'col-md-3';
                            serviceElement.innerHTML = `
                                <div class="card service-card">
                                    <div class="card-body service-card-body">
                                        <h5 class="card-title">${service.name}</h5>
                                        <p class="card-text">${service.description}</p>
                                        <p class="card-text"><small class="text-muted">By ${service.username}</small></p>
                                        <a href="#" class="btn btn-primary">View Details</a>
                                    </div>
                                </div>`;
                            searchResults.appendChild(serviceElement);
                        });
                    } else {
                        searchResults.innerHTML = '<div class="col-12">No services found</div>';
                    }
                });
        }

        document.getElementById('searchInput').addEventListener('input', searchServices);
    });
</script>
