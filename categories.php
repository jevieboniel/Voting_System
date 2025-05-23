<?php include('db_connect.php'); ?>
<style>
    body {
        background-color: lightgray;
    }
</style>

<div class="container py-5">
    <div class="row">
        <!-- Category Form Panel -->
        <div class="col-md-4"> <!-- Changed from col-md-5 to col-md-4 -->
            <form action="" id="manage-category">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Category Form</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="category" class="font-weight-bold">Category Name</label>
                            <input type="text" class="form-control" name="category" id="category" placeholder="Enter Category Name" required>
                        </div>
                    </div>
                    <div class="card-footer text-right bg-light">
                        <button type="submit" class="btn btn-success btn-sm px-4">Save</button>
                        <button type="button" class="btn btn-secondary btn-sm px-4" onclick="$('#manage-category').get(0).reset()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Category Table Panel -->
        <div class="col-md-8"> <!-- Changed from col-md-7 to col-md-8 -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Category List</h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped table-bordered mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center" style="width: 10%;">No.</th>
                                <th class="text-center">Category</th>
                                <th class="text-center" style="width: 25%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            $cats = $conn->query("SELECT * FROM category_list ORDER BY id ASC");
                            while($row = $cats->fetch_assoc()):
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $i++ ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['category']) ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-info edit_cat" type="button" 
                                            data-id="<?php echo $row['id'] ?>" 
                                            data-name="<?php echo htmlspecialchars($row['category']) ?>">
                                        Edit
                                    </button>
                                    <button class="btn btn-sm btn-danger delete_cat" type="button" 
                                            data-id="<?php echo $row['id'] ?>">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Script -->
<script>
    $('#manage-category').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_category',
            method: 'POST',
            data: $(this).serialize(),
            success: function(resp){
                if(resp == 1){
                    alert_toast("Category successfully added", 'success');
                } else if(resp == 2){
                    alert_toast("Category successfully updated", 'success');
                }
                setTimeout(() => location.reload(), 1500);
            }
        });
    });

    $('.edit_cat').click(function(){
        start_load();
        const form = $('#manage-category');
        form.get(0).reset();
        form.find("[name='id']").val($(this).data('id'));
        form.find("[name='category']").val($(this).data('name'));
        end_load();
    });

    $('.delete_cat').click(function(){
        _conf("Are you sure you want to delete this category?", "delete_cat", [$(this).data('id')]);
    });

    function delete_cat(id){
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_category',
            method: 'POST',
            data: { id: id },
            success: function(resp){
                if(resp == 1){
                    alert_toast("Category successfully deleted", 'success');
                    setTimeout(() => location.reload(), 1500);
                }
            }
        });
    }
</script>
