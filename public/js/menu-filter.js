document.addEventListener('DOMContentLoaded', () => {

    const filter = document.getElementById('priceFilter');

    if (!filter) return;

    filter.addEventListener('change', function () {

        const maxPrice = this.value;

        fetch('/menus/filter?maxPrice=' + maxPrice)
            .then(response => response.json())
            .then(data => {

                let container = document.getElementById('menu-container');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = "<p class='text-center'>Aucun menu trouvé.</p>";
                    return;
                }

                data.forEach(menu => {

                    container.innerHTML += `
                        <div class="col-md-4 mb-4">
                            <div class="card shadow-sm h-100">

                                <img 
                                    src="${menu.image ? '/uploads/menus/' + menu.image : '/images/default.jpg'}"
                                    class="card-img-top"
                                    style="height: 200px; object-fit: cover;"
                                    alt="Image"
                                >

                                <div class="card-body">
                                    <h5 class="card-title">${menu.titre}</h5>

                                    <p class="card-text">
                                        ${menu.description.replace(/<[^>]*>/g, '').substring(0,120)}...
                                    </p>

                                    <p>
                                        <strong>${menu.nombrePersonnesMinimum} personnes</strong><br>
                                        À partir de <strong>${menu.prix} €</strong>
                                    </p>

                                    <a href="/menus/${menu.id}" 
                                       class="btn btn-primary">
                                        Voir détail
                                    </a>
                                </div>

                            </div>
                        </div>
                    `;
                });

            });

    });

});