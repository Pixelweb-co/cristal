<br />
<div class="container-fluid">
    <div class="row mt-20" ng-controller="cartController" id="cartController">
        <div class="col-lg-8">
            <div class="table-cart">
                <table>
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código</th>
                            <th>Total</th>
                            <th>Cantidad</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr ng-repeat="x in items" style="position: relative;">
                            <td>

                                <div class="display-flex align-center">
                                    <div class="img-product">
                                        <img ng-src="{{x.image_url}}" alt="" class="mCS_img_loaded">
                                    </div>
                                    <div class="name-product">
                                        <p>{{x.post_title}}</p>
                                        <b>Categoria:</b> <span ng-repeat="cx in x.categorias" class="mr-2">{{cx.name}}</span>
                                    </div>
                                </div>
            </div>
            <div class="observ_field ">
                <b>Observación:</b>
                <textarea class="form-control" ng-model="x.observacion" placeholder="Escribe alguna observacíon adicional"></textarea>

            </div>
            </td>
            <td class="code-product">{{x.sku}}</td>
            <td>
                <div class="total">
                    {{x.subtotal | currency: '$'}}
                </div>
            </td>

            <td class="product-count">
                <form action="#" class="count-inlineflex">
                    <div class="qtyminus" ng-click="setQty('minus',x.ID)" role="button">-</div>
                    <input type="text" name="quantity" disabled value="{{x.cnt}}" class="qty">
                    <div class="qtyplus" role="button" ng-click="setQty('add',x.ID)">+</div>
                </form>
            </td>
            <td class="text-center">
                <button type="button" class="btn  btn-xs" ng-click="removeItem(x.ID)">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </button>
                <button type="button" class="obs-toggle ml-4 btn btn-xs">
                    <i class=" fa fa-caret-up" aria-hidden="true"></i>

                </button>

            </td>

            </tr>
            </tbody>
            </table>

            <div class="total_order_d row">
                <div class="col-md-10 text-right total_order_v">Total Pedido:</div>
                <div class="col-md-2 text-center total_order_v"> {{total_order | currency: '$'}}</div>
            </div>
            <div class=" row mt-4 mb-3">
                <div class="col-md-4 text-center "><a class="btn btn-outline-generic" href="<?= site_url('/index.php/catalogo') ?>">Agregar mas productos</a></div>
                <div class="col-md-8 text-left "> <button type="button" class="btn btn-black-generic" data-toggle="modal" data-target="#modalAddProduct">Agregar nuevo producto</a></div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div style="padding-top: 20px;"><b>Adjuntar documentos e imagenes:</b></div>
                    <div class="file-upload" id="file-upload">


                        <div id="box" class="animated bounceIn image-upload-wrap">
                            <div class="words">

                                <div class="drag-text">
                                    <h3>Seleccione un archivo y arrastrelo aqui</h3>
                                </div>
                            </div>
                           
                        </div>

                        <div class="files" id="files"></div>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="enlaces-panel" style="padding-top: 20px;">
                        <div class="container">
                            <div class="row " style="margin-bottom: 30px;">
                                <div class="col-md-6"><b>Agregar enlaces:</b></div>
                                <div class="col-md-6 text-right"> </div>
                            </div>

                            <div class="row" ng-repeat="l in links">
                                <div class="col-md-6"><a href="#">{{l}}</a></div>
                                <div class="col-md-6 text-right">
                                    <button type="button" class="btn  btn-xs" ng-click="removeItem(l.link)">
                                        <i class="fa fa-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-10">

                                    <input type="text" class="form-control" id="urlAdd" placeholder="Escribe o pega tu enlace aqui!" />
                                </div>
                                <div class="col-md-2 text-right">

                                    <button type="button" class="btn  btn-xs" ng-click="addLink()">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <div class="col-md-12" ng-if="linkError" style="color:red">
                                    {{linkError}}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>






        </div>
        <!-- /.table-cart -->
    </div>
    <!-- /.col-lg-8 -->
    <div class="col-lg-4">
        <div class="cart-totals">


            <?php include('panelStats.php'); ?>
            <div class="btn-cart-totals">
                <button type="button" id="btnSaveOrder" class="btn btn-outline-generic" ng-click="guardarOrden()">
                    <div class="cart-loader"></div> <span class="title_btn">Enviar</span>
                </button>
            </div>

        </div>
        <?php include('modalAddProduct.php'); ?>


    </div>
</div>