<?php
include './includes/header.inc.php';
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}
$totalPage = getSize('blog') / 20;
$offset = ($page - 1) * 20;
?>
<main>
    <div class="container-fluid row ">
        <div class="col-9 blog_container">

        </div>
        <div class="col-3">
            <div class="create-post text-center mt-2">
                <button class="btn btn-outline-info" onclick="window.location.href = './newpost.php'">Create New Blog</button>
            </div>
            <div class="filter-box ">
                <div class="sort-radio p-2 border mt-2">
                    <h5 class="text-center">Sort Blogs By</h5>
                      <input type="radio" id="date_n_2_o" name="sort_order" value="date_n_2_o" checked>
                      <label for="date_n_2_o">Date(New to Old)</label><br>
                      <input type="radio" id="date_o_2_n" name="sort_order" value="date_o_2_n">
                      <label for="date_o_2_n">Date(Old to New)</label><br>
                      <input type="radio" id="upvote" name="sort_order" value="upvote">
                      <label for="upvote">Upvotes</label><br>
                </div>
                <div class="Type-checkbox p-2 border mt-3">
                    <h5 class="text-center">Type of Blogs</h5>
                      <input type="checkbox" id="editorial" name="blog_type" value="editorial" checked>
                      <label for="editorial">Editorials</label><br>
                      <input type="checkbox" id="tutorial" name="blog_type" value="tutorial" checked>
                      <label for="tutorial">Tutorials</label><br>
                </div>
                <div class="author-search border mt-3 p-2">
                    <h5 class="text-center">Search By Author</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" name="author" id="author">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="author_search">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="author-search border mt-3 p-2">
                    <h5 class="text-center">Search By KeyWord</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" name="keyword" id="keyword">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" name="keyword_search">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAUJJREFUSEu9le0xBEEURc9GQAhEgAzIgAjIABmQARmQgQwQARnYEIiAOlWv1avR3TO7NWOq9sdOd9/z+r6PWbHws1pYnx5gD7gEjoHDCOQdeAHugfWU4FqAuxDvabjnegxSAxjlQRx8BBTynY83uQLO042OepAhoET+FdYU4aGGIK3aCbuEVp8M0POP2GVULfEiJOQt/uy3cpIBJXoT2IxoEOZD2NU8kwHF+ynRD2/h2WouMuA7Tm3aG91zcwEsit1aluey6DWq7g/jX5O8bZlqjyVbHR2tRvsETjq9oOBz+H4L3ExptLInjwrr3BrPo8IBeJEEXTMYg+rmIC+ODTttcc9pzK0mZGxc29GOa4efogr5U1zPLU1nkutVyKZNVXOhC5kDIDRDzoCnEslcgALRzl9xX84JGP0e9D5MW68tfoMfCXxMGRHr0pcAAAAASUVORK5CYII=" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pagination">
        <ul id="pagin">
        </ul>
    </div>
</main>

<script>
    window.totalPages = Math.ceil(<?= $totalPage; ?>);
    
    $(document).ready(function() {
        var authorName = '';
        var keywords = sessionStorage.getItem("query");
        $('input[name="sort_order"]').on("change", function(e) {
            console.log($(this).val());
            filterBlogs();
        });
        $('input[name="blog_type"]').on("change", function() {
            console.log($(this));
            filterBlogs();
        });
        $('button[name="author_search"]').on("click", function() {
            authorName = $('input[name="author"]').val();
            filterBlogs();
        })
        $('button[name="keyword_search"]').on("click", function() {
            keywords = $('input[name="keyword"]').val();
            filterBlogs();
        })

        function filterBlogs() {
            var sort_order = getCheckedData("sort_order");
            var blog_type = getCheckedData("blog_type");
            console.log(sort_order);
            console.log(blog_type);
            console.log(authorName);
            console.log(keywords);
            $.ajax({
                url: './ajax/blog_filter.ajax.php',
                type: 'post',
                data: {
                    sort_order: sort_order,
                    blog_type: blog_type,
                    authorName: authorName,
                    keywords: keywords,
                    offset: <?= $offset; ?>
                },
                success: function(response) {
                    $('.blog_container').html(response);
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
        filterBlogs();
    });
</script>
<?php
    include './includes/footer.inc.php';
?>
<script>
    pagination(totalPages, <?= $page; ?>, 'blogs');
   
</script>