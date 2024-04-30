

<!-- line modal -->
<div class="modal fade" id="squarespaceModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true" ng-controller="relatedModalCtrl">
  <div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close w-5 h-5" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
		</div>
		<div class="modal-body p-0">
			
        <div class="row">

<div class="col">
  <div class="card ">
    <div class="card-header border-0">
      <h3 class="mb-0">Este articulo tiene materiales relacionados. <small>Elija cuales desea agregar al pedido</small></h3>
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th scope="col">Material</th>
            <th scope="col">Código</th>
            <th scope="col">Descripción</th>
            <th scope="col">Precio</th>
            <th scope="col">Cantidad</th>
           
          </tr>
        </thead>
        <tbody>
          <tr ng-repeat="rl in relateds">
            <th scope="row">
              <div class="media align-items-center">
                <a href="#" class="avatar rounded-circle mr-3">
                  <img alt="Image placeholder" ng-src="{{ r.thumbnail_prod }}" class="imgp">
                </a>
                <div class="media-body">
                  <span class="mb-0 text-sm">{{rl.post_title}}</span>
                </div>
              </div>
            </th>
            <td>
            <div class="d-flex align-items-center">
                <span class="mr-2">  
                         {{rl.sku}}
                </span>
              </div>
            </td>
            <td class="align-items-center text-center">
              <span class="badge badge-dot mr-4">
              <i class="fas fa-eye"></i>
              </span>
            </td>
            <td>
            <div class="d-flex align-items-center">
                <span class="mr-2">  
                      $ {{rl.price}}
                </span>
              </div>
            </td>
            <td>
              <div class="d-flex align-items-center">
                <span class="mr-2">  
                         <input type="number" class="form-control w-50"/>
                </span>
              </div>
            </td>
           
          </tr>
          
        </tbody>
      </table>
    </div>
    <div class="card-footer py-4">
      <nav aria-label="...">
        <ul class="pagination justify-content-end mb-0">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
              <i class="fas fa-angle-left"></i>
              <span class="sr-only">Previous</span>
            </a>
          </li>
          <li class="page-item active">
            <a class="page-link" href="#">1</a>
          </li>
          <li class="page-item">
            <a class="page-link" href="#">2 <span class="sr-only">(current)</span></a>
          </li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">
              <i class="fas fa-angle-right"></i>
              <span class="sr-only">Next</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </div>
</div>
</div>

		</div>
		<div class="modal-footer">
			<div class="btn-group btn-group-justified" role="group" aria-label="group button">
				<div class="btn-group" role="group">
					<button type="button" class="btn btn-outline-generic" data-dismiss="modal"  role="button">Close</button>
				</div>
				<div class="btn-group btn-delete hidden" role="group">
					<button type="button" id="delImage" class="btn btn-outline-generic" data-dismiss="modal"  role="button">Delete</button>
				</div>
				<div class="btn-group" role="group">
					<button type="button" id="saveImage" class="btn btn-outline-generic" data-action="save" role="button">Save</button>
				</div>
			</div>
		</div>
	</div>
  </div>
</div>