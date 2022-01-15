<?php
include './includes/header.inc.php';
$limit = 50;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$offset = ($page - 1) * $limit;
?>

<main>
    <div class="container-fluid row ">
        <div class="col-9 user_container">
            
        </div>
        <div class="col-3">
            <div class="filter-box ">
                <div class="sort-radio p-2 border mt-2">
                    <h5 class="text-center">Sort Users By</h5>
                      <input type="radio" id="date_n_2_o" checked name="sort_order" value="date_n_2_o">
                      <label for="date_n_2_o">Join Date(New to Old)</label><br>
                      <input type="radio" id="date_o_2_n" name="sort_order" value="date_o_2_n">
                      <label for="date_o_2_n">Join Date(Old to New)</label><br>
                      <input type="radio" id="contribution" name="sort_order" value="contribution">
                      <label for="html">Contribution</label><br>
                      <input type="radio" id="username" name="sort_order" value="username">
                      <label for="css">Username</label><br>
                </div>
                <div class="problem-name-search border mt-3 p-2">
                    <h5 class="text-center">Search By Username</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" name="name">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="name-search">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="problem-name-search border mt-3 p-2">
                    <h5 class="text-center">Search By Country</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" name="country">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="country-search">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    $(document).ready(function() {
        var name = sessionStorage.getItem("query");
        var country = '';
        $('input[name="sort_order"]').on("change", function() {
            filterUsers();
        });
        $('button[name="name-search"]').on("click", function() {
            name = $('input[name="name"]').val();
            filterUsers();
        })
        $('input[name="name"]').on("change", function() {
            name = $('input[name="name"]').val();
        })
        $('button[name="country-search"]').on("click", function() {
            country = $('input[name="country"]').val();
            filterUsers();
        })
        $('input[name="country"]').on("click", function() {
            country = $('input[name="country"]').val();
        })

        function filterUsers() {
            var sort_order = getCheckedData("sort_order");
            console.log(sort_order);
            $.ajax({
                url: './ajax/user_filter.ajax.php',
                type: 'post',
                data: {
                    sort_order: sort_order,
                    name: name,
                    country: country,
                    offset: <?= $offset; ?>
                },
                success: function(response) {
                    $('.user_container').html(response);
                    //console.log(response);
                }
            })

        }

        function getCheckedData(name) {
            var filterData = [];
            $('input[name=' + name + ']:checked').each(function() {
                filterData.push($(this).val());
            })
            return filterData;
        }
        filterUsers();
    })
</script>