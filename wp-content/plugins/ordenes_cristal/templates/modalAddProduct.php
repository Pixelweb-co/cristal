        <!-- The Modal search-->
        <div class="modal cart" id="modalAddProduct" style="margin-top:50px;">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">Agregar nuevo producto a la orden</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <form id="prdForm">

                            <div class="container">
                            <div class="form-group">
                                    <label>Código</label>
                                    <input type="text" name="sku" class="form-control">
                                </div>
                            <div class="form-group">
                                    <label for="post_title">Nombre</label>
                                    <input type="text" name="post_title" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Descripción</label>
                                    <textarea name="post_content" class="form-control"></textarea>
                                </div>


                                <div class="form-group">
                                    <label>Categoria</label>
                                    <select name="categorias" class="form-control" ng-model="categoriaSeleccionada">
                                        <option value="">Categoria</option>
                                        <?php foreach ($categorias as $cat) {
                                            if ($cat->name != 'Sin categorizar') {
                                        ?>
                                                <option value="<?= $cat->term_id ?>"><?= $cat->name ?></option>
                                        <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cantidad</label>
                                    <input type="number" name="cnt" class="form-control">
                                </div>        
                                <div class="form-group">
                                    <label>Precio</label>
                                    <input type="number" name="price" class="form-control">
                                </div>

                                <div class="form-group">
                                    <label>Observación</label>
                                    <textarea name="observacion" class="form-control"></textarea>
                                </div>

                            </div>


                        </form>

                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-generic" onclick="AddNewProduct()">Guardar</button>

                        <button type="button" class="btn btn-outline-generic" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>
            </div>


        </div>