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
            
                <div class="container">
                <form id="prdForm">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nombre</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label >Descripción</label>
                        <textarea class="form-control"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label >Categoria</label>
                        <select class="form-control" ng-model="categoriaSeleccionada">
                                <option value="">Categoria</option>
                                <?php foreach($categorias as $cat){
                                    if($cat->name != 'Sin categorizar'){
                                    ?>
                                    <option value="<?=$cat->term_id?>"><?=$cat->name?></option>
                                <?php } 
                                }
                                ?>        
                            </select>
                    </div>
                    
                    <div class="form-group">
                        <label >Precio</label>
                        <input type="text" class="form-control">
                    </div>
                    

                    </form>            

                </div>

            <!-- Modal footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-outline-generic" >Guardar</button>

                <button type="button" class="btn btn-outline-generic" data-dismiss="modal">Cerrar</button>
            </div>

            </div>
        </div>


        </div>