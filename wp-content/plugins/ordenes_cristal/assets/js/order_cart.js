function closeModal() {
  console.log("closeModal", $("#modalSearchProducts"));
  $("#modalSearchProducts").modal("hide");
}

var app = angular.module("shoppingCart", []);

app.directive("scroll2", function () {
  return {
    link: function (scope, element, attrs) {
      element.bind("wheel", function () {
        console.log("Scrolled below header.");
      });
    },
  };
});
var loding = false;
app.directive("directiveWhenScrolled", function () {
  console.log("Directive When Scrolled");
  return {
    link: function (scope, elm, attr) {
     
      var raw = elm[0];
      elm.bind("wheel", function () {
        console.log("scrollHeight. ", raw.scrollHeight);
        console.log("scrollTop. ", jQuery(raw).offset().top);
        console.log("offsetHeight. ", jQuery(window).outerHeight());
        var scroll = jQuery(window).scrollTop();
        console.log("scrolltopjq . ", scroll);
        console.log(
          "restante",
          jQuery(raw).offset().top - jQuery(window).outerHeight()
        );
        if (
          scroll + jQuery(window).outerHeight() >= raw.scrollHeight &&
          !loding
        ) {
         
          scope.$apply(attr.directiveWhenScrolled);
          loding = true;
          console.log("wheelx");
        } else {
          loding = false;
        }
      });
    },
  };
});

app.controller("CartHeaderController", function ($scope, $http) {
  console.log("CartHeaderController");
  $scope.init = () => {
    var data = JSON.parse(localStorage.getItem("OrderCart"));
    if (!data || data.length == 0) {
      console.log("init no hay pedido");
      jQuery('#main-nav ul li a[title="Pedido"]').hide();
      localStorage.removeItem("marca_sel");
    }

    if (localStorage.getItem("search") && document.getElementById("primary")) {
      var term = localStorage.getItem("search");

      document.getElementById("searchField").value = term;

      setBusqueda(term);
      localStorage.removeItem("search");
    }
  };

  $scope.init();

  $scope.searchTerm = (el) => {
    console.log("el", document.getElementById("searchField").value);

    var term = document.getElementById("searchField").value;

    if (term == "") {
      return false;
    }

    if (!document.getElementById("primary")) {
      localStorage.setItem("search", term);
      location.href = base_url;
      return false;
    }
    setBusqueda(term);
  };
});

app.controller("relatedModalCtrl", function ($scope, $http) {
  $scope.relateds = [];
  $scope.addRelatedsCart = () => {
    console.log("relteds in crtl ", $scope.relateds);
    enviarEventoConProductosRelacionados($scope.relateds);
  };
});

// setBusqueda
function setBusqueda(term) {
  // Crear un nuevo evento personalizado
  var evento = new CustomEvent("setbusquedam", { detail: term });

  console.log("from searchctrl fn", term);
  // Dispatch del evento
  window.dispatchEvent(evento);
}

// Escuchar el evento y setbusqueda
window.addEventListener("setbusquedam", function (event) {
  console.log("setBusqueda", event);
  var termino = event.detail;
  angular.element(document).ready(function () {
    // Obtener el alcance de AngularJS del elemento MiniCart
    var scope = angular.element(document.getElementById("primary")).scope();

    isCartPage = document.getElementById("cartController");

    if (isCartPage) {
      localStorage.setItem("search", termino);
      location.href = base_url;
      return false;
    }

    // Aplicar los cambios en el alcance
    scope.$apply(function () {
      // Llamar a la función loadData en el controlador correspo  ndiente para actualizar los datos del mini carrito
      //scope.sento_cart(event.detail);
      scope.term = termino;
      scope.buscarProductos();

      console.log("busqueda ev:", termino);
    });
  });
});

// Función para crear y enviar un evento con parámetros
function enviarEventoConProductosRelacionados(lista) {
  // Crear un nuevo evento personalizado
  var evento = new CustomEvent("setCartR", { detail: lista });

  // Dispatch del evento
  window.dispatchEvent(evento);
}

// Escuchar el evento y mostrar la lista en la consola
window.addEventListener("setCartR", function (event) {
  console.log("Lista recibida ev select:", event.detail);

  angular.element(document).ready(function () {
    // Obtener el alcance de AngularJS del elemento MiniCart
    var scope = angular.element(document.getElementById("primary")).scope();

    jQuery("#squarespaceModal").modal("hide");
    // Aplicar los cambios en el alcance
    scope.$apply(function () {
      // Llamar a la función loadData en el controlador correspo  ndiente para actualizar los datos del mini carrito
      scope.sento_cart(event.detail);
    });
  });
});

// Función para crear y enviar un evento con parámetros
function enviarEventoConParametros(lista) {
  // Crear un nuevo evento personalizado
  var evento = new CustomEvent("listaEnviada", { detail: lista });

  // Dispatch del evento
  window.dispatchEvent(evento);
}

// Escuchar el evento y mostrar la lista en la consola
window.addEventListener("listaEnviada", function (event) {
  console.log("Lista recibida:", event.detail);

  angular.element(document).ready(function () {
    // Obtener el alcance de AngularJS del elemento MiniCart
    var scope = angular
      .element(document.getElementById("squarespaceModal"))
      .scope();

    jQuery("#squarespaceModal").modal("show");
    // Aplicar los cambios en el alcance
    scope.$apply(function () {
      // Llamar a la función loadData en el controlador correspo  ndiente para actualizar los datos del mini carrito
      scope.relateds = event.detail;
    });
  });
});

// Escuchar el evento y mostrar la lista en la consola
window.addEventListener("RelationsToCart", function (event) {
  console.log("Lista recibida:", event.detail);

  angular.element(document).ready(function () {
    // Obtener el alcance de AngularJS del elemento MiniCart
    var scope = angular
      .element(document.getElementById("squarespaceModal"))
      .scope();

    jQuery("#squarespaceModal").modal("show");
    // Aplicar los cambios en el alcance
    scope.$apply(function () {
      // Llamar a la función loadData en el controlador correspo  ndiente para actualizar los datos del mini carrito
      scope.relateds = event.detail;
    });
  });
});

app.controller("cartSearchController", function ($scope, $http) {
  console.log("controllerSearch");
  $scope.marcaSeleccionada = null;
  $scope.categoriaSeleccionada = null;
  $scope.nombreProducto = null;
  $scope.tiendaSeleccionada = null;
  $scope.sresult = [];
  $scope.marcaNameSel = null;
  $scope.tiendas = [];
  $scope.items_to_cart = [];
  // Otras funciones y variables del controlador
  $scope.page = 1; // Página actual
  $scope.perPage = 5; // Cantidad de productos por página
  $scope.limit = 5;
  $scope.total_pages = 0;
  $scope.last_page = 0;

  //busqeda termino
  $scope.term = "";

  // Obtener el alcance de AngularJS del elemento MiniCart
  $scope.relpd = [];

  $scope.init = () => {
    var data = JSON.parse(localStorage.getItem("OrderCart"));
    if (!data || data.length == 0) {
      console.log("init no hay pedido");
      jQuery('#main-nav ul li a[title="Pedido"]').hide();
      localStorage.removeItem("marca_sel");
    }

    //term query
    if (
      localStorage.getItem("search") &&
      localStorage.getItem("search") != ""
    ) {
      $scope.term = localStorage.getItem("search");
      $scope.buscarProductos();
      console.log("busqueda en cache");
      localStorage.removeItem("search");
      $scope.term = "";
    }

    if(localStorage.getItem("marca_sel")){

      $scope.marca_sel = localStorage.getItem("marca_sel");
      
      $scope.marcaSeleccionada = localStorage.getItem("name_marca");
      // Almacenar los datos de la tienda seleccionada en el localStorage
      $scope.tiendaSeleccionada = localStorage.getItem("tiendaSeleccionada");

      $scope.marcaNameSel =  localStorage.getItem("name_marca");

      jQuery("#filter-area .marco-marca-m").removeClass("selected");
      jQuery("#menuma-" + $scope.marca_sel).addClass("selected");

    }



  };

  setTimeout(function () {
    $scope.init();

    console.log("nm",$scope.marcaNameSel)
  }, 500);

  $scope.setMarca = (id, image_url, name_marca) => {
    $scope.page = 1;
    $scope.limit = 5;
    $scope.term = "";

    
    if (localStorage.getItem("marca_sel")) {
      var items_order = JSON.parse(localStorage.getItem("OrderCart"));
      console.log("new ", id);
      console.log("old ", localStorage.getItem("marca_sel"));
      if (
        parseInt(localStorage.getItem("marca_sel")) !== id &&
        items_order != null &&
        items_order.length > 0
      ) {
        Swal.fire({
          title:
            "Ya hay un pedido con la marca seleccionada, desea cancelar el pedido actual e iniciar uno nuevo con esta marca que desea?",
          showDenyButton: false,
          showCancelButton: true,
          confirmButtonText: `Iniciar nuevo pedido`,
          cancelButtonText: `Cancelar`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            $scope.sresult = [];
            console.log("limpiando", $scope.sresult);
            $scope.marcaNameSel = name_marca;

            localStorage.setItem("OrderCart", JSON.stringify([]));
            localStorage.setItem("marca_sel", id);
            localStorage.setItem("marca_sel_image", image_url);
            localStorage.setItem("name_marca", name_marca);

            $scope.marcaSeleccionada = id;
            console.log("setsel", id);
            $scope.$apply();

            jQuery("#filter-area .marco-marca-m").removeClass("selected");
            jQuery("#menuma-" + id).addClass("selected");
            Swal.fire("Marca seleccionada!", "", "success");
          } else if (result.isDenied) {
            //Swal.fire('Changes are not saved', '', 'info')
          }
        });
      } else {
        $scope.sresult = [];
        localStorage.setItem("marca_sel", id);
        localStorage.setItem("marca_sel_image", image_url);
        localStorage.setItem("name_marca", name_marca);
        $scope.marcaSeleccionada = id;
        console.log("new setsel", id);

        jQuery("#filter-area .marco-marca-m").removeClass("selected");
        jQuery("#menuma-" + id).addClass("selected");
        $scope.marcaNameSel = name_marca;
      }
    } else {
      $scope.sresult = [];

      localStorage.setItem("marca_sel", id);
      localStorage.setItem("marca_sel_image", image_url);
      localStorage.setItem("name_marca", name_marca);
      $scope.marcaSeleccionada = id;
      console.log("new setsel", id);

      jQuery("#filter-area .marco-marca-m").removeClass("selected");
      jQuery("#menuma-" + id).addClass("selected");
    }
    console.log("sresult", $scope.sresult);
  };

  $scope.setTienda = (id, image_url) => {
    $scope.page = 1;
    $scope.limit = 3;
    if (localStorage.getItem("marca_sel")) {
      var items_order = JSON.parse(localStorage.getItem("OrderCart"));
      console.log("new ", id);
      console.log("old ", localStorage.getItem("marca_sel"));
      if (
        localStorage.getItem("marca_sel") !== id &&
        items_order != null &&
        items_order.length > 0
      ) {
        Swal.fire({
          title:
            "Ya hay un pedido con la marca seleccionada, desea cancelar el pedido actual e iniciar uno nuevo con esta marca que desea?",
          showDenyButton: false,
          showCancelButton: true,
          confirmButtonText: `Iniciar nuevo pedido`,
          cancelButtonText: `Cancelar`,
        }).then((result) => {
          /* Read more about isConfirmed, isDenied below */
          if (result.isConfirmed) {
            $scope.sresult = [];
            console.log("limpiando", $scope.sresult);

            localStorage.setItem("OrderCart", JSON.stringify([]));
            localStorage.setItem("marca_sel", id);
            localStorage.setItem("marca_sel_image", image_url);
            localStorage.setItem("name_marca", name_marca);

            $scope.marcaSeleccionada = id;
            console.log("setsel", id);
            $scope.$apply();

            jQuery("#filter-area .marco-marca-m").removeClass("selected");
            jQuery("#menuma-" + id).addClass("selected");
            Swal.fire("Marca seleccionada!", "", "success");
          } else if (result.isDenied) {
            //Swal.fire('Changes are not saved', '', 'info')
          }
        });
      } else {
        $scope.sresult = [];
        localStorage.setItem("marca_sel", id);
        localStorage.setItem("marca_sel_image", image_url);
        localStorage.setItem("name_marca", name_marca);
        $scope.marcaSeleccionada = id;
        console.log("new setsel", id);

        jQuery("#filter-area .marco-marca-m").removeClass("selected");
        jQuery("#menuma-" + id).addClass("selected");
      }
    } else {
      $scope.sresult = [];

      localStorage.setItem("marca_sel", id);
      localStorage.setItem("marca_sel_image", image_url);
      localStorage.setItem("name_marca", name_marca);
      $scope.marcaSeleccionada = id;
      console.log("new setsel", id);

      jQuery("#filter-area .marco-marca-m").removeClass("selected");
      jQuery("#menuma-" + id).addClass("selected");
    }
    console.log("sresult", $scope.sresult);
  };

  $scope.buscarProductos = async function () {
    jQuery("#primary .cart-loader").show();

    if ($scope.term == "") {
      $marca_sel = localStorage.getItem("marca_sel");

      if (!$marca_sel) {
        Swal.fire({
          title: "Te falta seleccionar la marca",
          text: "Para realizar la busqueda debes selecionar una marca de el listado",
          icon: "info",
        });
        jQuery("#primary .cart-loader").hide();

        return false;
      }

      var parametros = {
        page: $scope.page,
      };
      if ($scope.marcaSeleccionada) {
        parametros.marca = $scope.marcaSeleccionada;
      }
      if ($scope.categoriaSeleccionada) {
        parametros.categoria = $scope.categoriaSeleccionada;
      }
      if ($scope.nombreProducto) {
        parametros.nombre = $scope.nombreProducto;
      }

      if ($scope.tiendaSeleccionada) {
        $http
          .get(base_url + "/wp-json/ordenes_cristal/v1/obtener_tiendas")
          .then(function (response) {
            // Asignar las tiendas obtenidas a $scope.tiendas
            $scope.tiendas = response.data;

            parametros.tienda = $scope.tiendaSeleccionada;
            console.log("tid ", parametros.tienda);
            localStorage.setItem(
              "tiendaSeleccionada",
              $scope.tiendaSeleccionada
            );
            console.log("tiendaS", $scope.tiendas);
            const tienda_name = $scope.tiendas.find(
              (t) => t.metros_cuadrados == $scope.tiendaSeleccionada
            ).titulo;
            console.log("Ts ", tienda_name);

            localStorage.setItem("tienda_name", tienda_name);
          })
          .catch(function (error) {
            console.error("Error al obtener las tiendas:", error);
          });
      } else {
        Swal.fire({
          title: "Te falta seleccionar la tienda",
          text: "Para realizar la busqueda debes selecionar una tienda de el listado",
          icon: "info",
        });
        jQuery("#primary .cart-loader").hide();

        return false;
      }
    } else {
      var parametros = { term: $scope.term };
    }

    console.log("parametros", parametros);
    // Lógica para realizar la búsqueda de productos con los parámetros seleccionados
    $http({
      method: "POST",
      url: base_url + "/wp-json/ordenes_cristal/v1/buscar_productos",
      params: parametros,
      headers: { "X-WP-Nonce": nonce },
    }).then(function (response) {
      // Manejar la respuesta de la búsqueda de productos
      var tmpl = [];
      console.log("Tienda ", response.data.tienda);
      $scope.sresult = response.data.productos;
      if ($scope.sresult == 0) {
        $scope.marcaNameSel = "No se encontraron resultados";
      }
      $scope.sresult.forEach((item) => {
        tmpl.push({ ...item });
      });

      $scope.sresult = tmpl;
      console.log("data load results cnt ", $scope.sresult);

      $scope.page++;
      $scope.limit += parseInt(response.data.for_page);
      $scope.total_pages = response.data.num_pages;

      $scope.smarca = response.data.marca;
      $scope.stienda = response.data.tienda;
      jQuery("#primary .cart-loader").hide();
    });
  };

  // Función para cargar más elementos cuando se activa el scroll infinito
  $scope.cargarMas = async function () {
    console.log("pages", $scope.total_pages);
    console.log("page", $scope.page);
    if ($scope.page > $scope.total_pages) {
      return true;
    }

    if($scope.last_page < $scope.page){
     

    // Realizar una solicitud HTTP para obtener más productos
    console.log("cargar mas");
    var parametros = {
      marca: $scope.marcaSeleccionada,
      categoria: $scope.categoriaSeleccionada,
      nombre: $scope.nombreProducto,
      tienda: $scope.tiendaSeleccionada,
      page: $scope.page, // Página actual para paginación
    };

    await $http({
      method: "POST",
      url: base_url + "/wp-json/ordenes_cristal/v1/buscar_productos",
      params: parametros,
      headers: { "X-WP-Nonce": nonce },
    }).then(function (response) {
      // Manejar la respuesta de la búsqueda de productos
      // Concatenar los nuevos productos al array existente

      var tmpl = [];
      response.data.productos.forEach((item) => {
        tmpl.push({ ...item });
      });
      console.log(tmpl);
      // Filtrar elementos duplicados
      var uniqueResults = [];
      tmpl.forEach(function(item) {
        var found = $scope.sresult.some(function(existingItem) {

          return existingItem.ID === item.ID; // Suponiendo que cada objeto tiene una propiedad 'id'
        });
        if (!found) {
          uniqueResults.push(item);
        }
      });

      // Concatenar los nuevos elementos sin duplicados
      $scope.sresult = $scope.sresult.concat(uniqueResults);

      // Incrementar el número de página para la próxima carga
      $scope.page++;
      $scope.limit += parseInt(response.data.for_page);
      $scope.total_pages = response.data.num_pages;
    });

    
    }

    return true;
  };

  $scope.getRelatedProducts = function (id) {
    $http({
      method: "POST",
      url: base_url + "/wp-json/ordenes_cristal/v1/get_related",
      params: { id: id },
      headers: { "X-WP-Nonce": nonce },
    }).then(function (response) {
      // Manejar la respuesta de la búsqueda de productos
      if (response.data.length > 0) {
        $scope.relpd = [];

        response.data.forEach((item) => {
          $scope.relpd.push({ ...item });
        });
      }
    });
  };

  $scope.sento_cart = (items) => {
    $scope.items_to_cart = [...$scope.items_to_cart, ...items];
    console.log("items a enviar", $scope.items_to_cart);
    $scope.items_to_cart.forEach((product_add) => {
      console.log("padd-item ", product_add);

      if (!localStorage.getItem("OrderCart")) {
        console.log("no existe localstorass");

        localStorage.setItem(
          "OrderCart",
          JSON.stringify([
            {
              ...product_add,
              cnt: product_add.cnt,
              subtotal: parseInt(product_add.price),
            },
          ])
        );
      } else {
        var data = JSON.parse(localStorage.getItem("OrderCart"));
        console.log("mini carrito", data);

        var found = data.find((i) => i.ID == product_add.ID);

        if (!found && product_add.cnt > 0) {
          console.log("no found", found);

          data.push({
            ...product_add,
            cnt: parseFloat(product_add.cnt),
            subtotal: parseInt(product_add.price),
            price: parseInt(product_add.price),
          });

          localStorage.setItem("OrderCart", JSON.stringify(data));
        }

        if (found && product_add.cnt > 0) {
          console.log("founddd ", found);

          found.cnt = parseFloat(found.cnt + product_add.cnt);
          found.subtotal = parseInt(found.price) * parseFloat(product_add.cnt);
          found.price = parseInt(found.price);

          var new_data = data.filter((i) => i.ID != product_add.ID);
          new_data.push(found);
          console.log("nwdta", new_data);

          localStorage.setItem("OrderCart", JSON.stringify(new_data));

          console.log("act localstorass", new_data);
        }
        var data = JSON.parse(localStorage.getItem("OrderCart"));
        if (data.length > 0) {
          jQuery('#main-nav ul li a[title="Pedido"]').show();
        }
      }
    });

    new Noty({
      type: "success",
      layout: "center",
      text: "<b>Se agrego el producto al pedido correctamente!<br/>",
      timeout: 6000,
      buttons: [
        Noty.button(
          "VER PEDIDO ACTUAL",
          "btn btn-outline-generic",
          function () {
            location.href = base_url + "/index.php/hacer_pedido/";
          },
          { id: "button1", "data-status": "ok" }
        ),
      ],
    }).show();
  };

  $scope.addCart = (id_item, el) => {
    $scope.items_to_cart = [];

    var product_add = $scope.sresult.find((p) => p.ID === id_item);

    if (product_add.cnt > 0) {
      $scope.getRelatedProducts(id_item);
      console.log("padd", product_add);

      $scope.items_to_cart.push({
        ...product_add,
        cnt: parseFloat(product_add.cnt),
        subtotal: parseInt(product_add.price),
        price: parseInt(product_add.price),
      });

      setTimeout(() => {
        console.log("relp: ", $scope.relpd);
        console.log($scope.relpd);

        if ($scope.relpd.length > 0) {
          console.log("tiene relacionados");

          enviarEventoConParametros($scope.relpd);
        } else {
          $scope.sento_cart([]);
        }
      }, 1000);
    }
  };

  $scope.setQty = (element, id_item) => {
    console.log("id_item", id_item);
    console.log("element", element.target.value);
    var data = JSON.parse(localStorage.getItem("OrderCart"));
    var found = data.find((i) => i.ID == id_item);

    if (action == "add") {
      found.cnt = found.cnt + 1;
    } else {
      found.cnt = found.cnt - 1;
    }

    if (found.cnt <= 0) {
      $scope.removeItem(id_item);
      return false;
    }

    found.subtotal = parseInt(found.price) * parseInt(found.cnt);
    var new_data = data.filter((i) => i.ID != id_item);
    new_data.push(found);
    console.log("nwdta_rp", new_data);

    localStorage.setItem("OrderCart", JSON.stringify(new_data));

    console.log("update qty", new_data);
    $scope.items = new_data;
    $scope.loadData();
    $scope.calculate();
  };
});

app.controller("cartOrderListController", function ($scope, $http) {
  console.log("cartOrderListController", $scope);
});

app.controller("miniCartController", function ($scope, $http) {
  console.log("miniCartController", $scope);

  $scope.totalize = () => {
    console.log("totalize");
    $scope.total_order = 0;

    var data = JSON.parse(localStorage.getItem("OrderCart"));
    if (!data) {
      return false;
    }

    console.log("citems_t", data);

    data.map((item, index) => {
      $scope.total_order += parseInt(item.subtotal);
    });
  };

  $scope.calculate = () => {
    // Objeto para almacenar los totales por categoría
    var totalsByCategory = {};

    // Obtener los datos del carrito del almacenamiento local
    var cartData = JSON.parse(localStorage.getItem("OrderCart"));

    // Verificar si hay datos en el carrito
    if (!cartData || cartData.length === 0) {
      return [];
    }
    var id_C = 0;
    // Calcular los totales por categoría
    cartData.forEach((item) => {
      // Obtener las categorías asociadas al producto
      var categories = item.categorias;

      // Verificar si el producto tiene categorías asociadas
      if (categories && categories.length > 0) {
        categories.forEach((category) => {
          // Obtener el nombre de la categoría
          var categoryName = category.name;
          if (
            categoryName != "Sin categorizar" ||
            categoryName != "Uncategorized" ||
            categoryName != "uncategorized"
          ) {
            id_C = category.ID;
            // Verificar si ya hay un total para esta categoría
            if (!totalsByCategory[categoryName]) {
              totalsByCategory[categoryName] = 0;
            }

            // Incrementar el total de la categoría con el subtotal del producto
            totalsByCategory[categoryName] += parseInt(item.subtotal);
          }
        });
      }
    });

    // Convertir el objeto totalsByCategory a un array de objetos
    var result = [];
    Object.keys(totalsByCategory).forEach((categoryName) => {
      result.push({
        ID: id_C,
        title: categoryName, // Usando el nombre de la categoría como título
        total_cat: parseInt(totalsByCategory[categoryName]),
      });
    });
    console.log("res por cat", result);
    return result;
  };

  $scope.loadData = function () {
    $scope.items =
      localStorage.getItem("OrderCart") != null
        ? JSON.parse(localStorage.getItem("OrderCart"))
        : [];

    console.log("items", $scope.items);

    $scope.totalize();
    $scope.calculate();
  };

  $scope.setQty = (element, id_item) => {
    console.log("id_item", id_item);
    console.log("element", element.target.value);
    return false;
    var data = JSON.parse(localStorage.getItem("OrderCart"));
    var found = data.find((i) => i.ID == id_item);

    if (action == "add") {
      found.cnt = found.cnt + 1;
    } else {
      found.cnt = found.cnt - 1;
    }

    if (found.cnt <= 0) {
      $scope.removeItem(id_item);
      return false;
    }

    found.subtotal = parseInt(found.price) * parseInt(found.cnt);
    var new_data = data.filter((i) => i.ID != id_item);
    new_data.push(found);
    console.log("nwdta_rp", new_data);

    localStorage.setItem("OrderCart", JSON.stringify(new_data));

    console.log("update qty", new_data);
    $scope.items = new_data;
    $scope.loadData();
    $scope.stats = $scope.calculate();
  };

  $scope.loadData();
});

app.controller("cartController", function ($scope, $http) {
  $scope.total_order = 0;
  $scope.stats = [];
  $scope.file_order_upload = null;
  $scope.links = [];
  $scope.linkError = null;
  $scope.valores_ideales = [];
  $scope.orden_id = null;
  $scope.orden_is_send = 0;


  if (localStorage.getItem("orden_id_edit")) {
    $scope.orden_id = parseInt(localStorage.getItem("orden_id_edit"));
  }
  if (localStorage.getItem("orden_is_send")) {
    $scope.orden_is_send = parseInt(localStorage.getItem("orden_is_send"));
  }

  

  // Obtener las tiendas desde el endpoint
  $http
    .get(base_url + "/wp-json/ordenes_cristal/v1/obtener_tiendas")
    .then(function (response) {
      // Asignar las tiendas obtenidas a $scope.tiendas
      console.log(response);
      $scope.tiendas = response.data;
    })
    .catch(function (error) {
      console.error("Error al obtener las tiendas:", error);
    });

  // Función para actualizar la cantidad de un ítem
  $scope.actualizarCantidad = function (item) {
    // Actualizar el modelo de datos con la nueva cantidad
    // Esto es necesario para que se refleje en la lista
    var data = JSON.parse(localStorage.getItem("OrderCart"));

    for (var i = 0; i < data.length; i++) {
      if (data[i].ID === item.ID && item.cnt) {
        if (data[i].cnt <= 0) {
          $scope.removeItem(data[i].ID);
          break;
        }

        data[i].cnt = parseFloat(item.cnt);
        data[i].subtotal = parseInt(data[i].price) * parseFloat(data[i].cnt);

        console.log("nw cant", data[i].cnt);
        break;
      }
    }

    console.log("nw cant", data);
    localStorage.setItem("OrderCart", JSON.stringify(data));
    $scope.items = data;
    $scope.loadData();
  };

  $scope.get_valores_ideales_order = async function () {
    // Obtener los datos del carrito del almacenamiento local
    var cartData = JSON.parse(localStorage.getItem("OrderCart"));
    console.log("datos carrito para viniciales ", cartData);
    // Verificar si hay datos en el carrito
    if (!cartData || cartData.length === 0) {
      return [];
    }

    //obtener todas las categorias del cart
    var categoriasCart = cartData.map((item) => item.categorias[0].term_id);
    const idsUnicos = [...new Set(categoriasCart)];
    console.log("categoria de consulta", idsUnicos);

    jQuery("#statsPanel .cart-loader").show();

    //Lógica para realizar la búsqueda de valores ideales segun categorias seleccionadas
    await $http({
      method: "POST",
      url: base_url + "/wp-json/ordenes_cristal/v1/obtener_valores_ideales",
      params: {
        categorias: idsUnicos.join(","),
        marca: localStorage.getItem("marca_sel"),
      },
      headers: { "X-WP-Nonce": nonce },
    }).then(function (response) {
      // Manejar la respuesta de la búsqueda de productos
      $scope.valores_ideales = response.data;
      console.log("result ideales ", response.data);
      $scope.loadData();
    });
  };

  $scope.get_valores_ideales_order();

  // Función para filtrar las tiendas según la opción seleccionada en el select
  $scope.filtrarTiendas = function () {
    // Realizar alguna acción con la tienda seleccionada (por ejemplo, realizar otra llamada al servidor)
    console.log("Tienda seleccionada:", $scope.filtroTienda);
  };

  $scope.validarURL = (url) => {
    // Expresión regular para validar una URL
    var urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;

    // Verifica si la URL cumple con el formato esperado
    if (urlRegex.test(url)) {
      return true; // La URL es válida
    } else {
      return false; // La URL no es válida
    }
  };

  $scope.addLink = () => {
    var link = document.getElementById("urlAdd").value;
    console.log(link);
    if ($scope.validarURL(link)) {
      $scope.links.push(link);
      document.getElementById("urlAdd").value = "";
    } else {
      $scope.linkError = "Agrega un enlace valido";
    }
  };

  $scope.removeItem = function (id) {
    // Encuentra el índice del elemento con el ID especificado
    var index = $scope.items.findIndex(function (item) {
      return item.ID === id;
    });
    // Si se encontró el elemento, elimínalo del arreglo
    if (index !== -1) {
      $scope.items.splice(index, 1);
      // Actualiza localStorage eliminando el elemento correspondiente
      var cartData = JSON.parse(localStorage.getItem("OrderCart"));
      if (cartData) {
        var updatedCartData = cartData.filter(function (item) {
          return item.ID !== id;
        });
        localStorage.setItem("OrderCart", JSON.stringify(updatedCartData));
      }
    }
    // Recalcula el total del pedido
    $scope.totalize();
    $scope.calculate();
  };

  $scope.loadData = function () {
    $scope.items = $scope.ordenarPorPropiedad(
      localStorage.getItem("OrderCart") != null
        ? JSON.parse(localStorage.getItem("OrderCart"))
        : [],
      "ID",
      true
    );

    console.log("items loaded fl ", $scope.items);
    $scope.totalize();
    $scope.calculate();
  };

  $scope.totalize = () => {
    console.log("totalize cart");
    $scope.total_order = 0;
    var data = JSON.parse(localStorage.getItem("OrderCart"));
    if (!data) {
      return false;
    }

    console.log("citems_t", data);

    data.map((item, index) => {
      $scope.total_order += parseInt(item.subtotal);
    });
  };

  $scope.calculate = async () => {
    // Convertir el objeto totalsByCategory a un array de objetos
    var result = [];

    var id_C = 0;
    // Objeto para almacenar los totales por categoría
    var totalsByCategory = {};
    var idsByCategory = {};

    // Obtener los datos del carrito del almacenamiento local
    var cartData = JSON.parse(localStorage.getItem("OrderCart"));

    // Verificar si hay datos en el carrito
    if (
      !cartData ||
      (cartData.length === 0 && $scope.valores_ideales.left.length > 0)
    ) {
      return [];
    }

    // Calcular los totales por categoría
    cartData.forEach((item) => {
      // Obtener las categorías asociadas al producto
      var categories = item.categorias;

      // Verificar si el producto tiene categorías asociadas
      if (categories && categories.length > 0) {
        categories.forEach((category) => {
          // Obtener el nombre de la categoría
          if (!category.term_id) {
            return false;
          }
          var categoryName = category.name;
          if (
            categoryName != "Sin categorizar" ||
            categoryName != "Uncategorized" ||
            categoryName != "uncategorized"
          ) {
            id_C = category.term_id;
            // Verificar si ya hay un total para esta categoría
            if (!totalsByCategory[categoryName]) {
              totalsByCategory[categoryName] = 0;
              idsByCategory[categoryName] = category.term_id;
            }

            // Incrementar el total de la categoría con el subtotal del producto
            totalsByCategory[categoryName] += item.subtotal;
          }
        });
      }
    });

    Object.keys(totalsByCategory).forEach((categoryName) => {
      var metro_cuadrado_total_categoria =
        totalsByCategory[categoryName] /
        parseInt(localStorage.getItem("tiendaSeleccionada")); //valor metro cuadrado categoria
      var valor_ideal = $scope.valores_ideales.find(
        (val) =>
          val.marca_id == localStorage.getItem("marca_sel") &&
          val.categoria_id == idsByCategory[categoryName]
      ).valor_ideal; // valor ideal marca ctegoria;
      var valor_restante = valor_ideal - metro_cuadrado_total_categoria; //valor ideal restante;

      var porcentaje_utilizado = 30;

      var pocentaje_utilizado_init =
        (metro_cuadrado_total_categoria / valor_ideal) * 100;
      if (pocentaje_utilizado_init >= 30) {
        porcentaje_utilizado = pocentaje_utilizado_init;
      }
      if (pocentaje_utilizado_init <= 30) {
        porcentaje_utilizado = 30;
      }

      var porcentaje_restante =
        100 -
        (totalsByCategory[categoryName] /
          parseInt(localStorage.getItem("tiendaSeleccionada")) /
          parseInt(
            $scope.valores_ideales.find(
              (val) =>
                val.marca_id == localStorage.getItem("marca_sel") &&
                val.categoria_id == idsByCategory[categoryName]
            ).valor_ideal
          )) *
          100;

      if (porcentaje_utilizado >= 80) {
        porcentaje_utilizado = 80;
        porcentaje_restante = 20;
      }

      var overvalue = false;

      //si supera valor ideal
      if (metro_cuadrado_total_categoria > valor_ideal) {
        overvalue = true;
      }

      result.push({
        ID: idsByCategory[categoryName],
        title: categoryName, // Usando el nombre de la categoría como título
        metro_cuadrado_total_categoria: metro_cuadrado_total_categoria,
        total_cat: totalsByCategory[categoryName],
        valor_restante: valor_restante,
        valor_ideal: valor_ideal,
        porcentaje_utilizado: porcentaje_utilizado,
        porcentaje_restante: porcentaje_restante,
        overvalue: overvalue,
      });

      console.log("res por cat", result);
      $scope.stats = $scope.ordenarPorPropiedad(result, "title", true);
      jQuery("#statsPanel .cart-loader").hide();
    });
  };

  $scope.ordenarPorPropiedad = (array, propiedad, ascendente = true) => {
    return array.sort((a, b) => {
      const valorA = a[propiedad];
      const valorB = b[propiedad];
      let comparacion = 0;

      if (valorA > valorB) {
        comparacion = 1;
      } else if (valorA < valorB) {
        comparacion = -1;
      }

      return ascendente ? comparacion : comparacion * -1;
    });
  };

  $scope.setObservation = (item_id, observation) => {
    console.log("set", observation);
  };

  $scope.addCart = (id_item) => {
    var product_add = $scope.sresult.find((p) => p.ID === id_item);
    console.log("padd", product_add);

    if (!localStorage.getItem("OrderCart")) {
      console.log("no existe localstorass");

      localStorage.setItem(
        "OrderCart",
        JSON.stringify([{ ...product_add }])
      );
    } else {
      var data = JSON.parse(localStorage.getItem("OrderCart"));
      console.log("carrito", data);

      var found = data.find((i) => i.ID == product_add.ID);

      if (!found) {
        console.log("no found", found);

        data.push({
          ...product_add,
          cnt: 1,
          subtotal: parseInt(product_add.price),
        });

        localStorage.setItem("OrderCart", JSON.stringify(data));
      } else {
        console.log("founddd ", found);

        found.cnt = found.cnt + 1;
        found.subtotal = found.price * found.cnt;

        var new_data = data.filter((i) => i.ID != product_add.ID);
        new_data.push(found);
        console.log("nwdta", new_data);

        localStorage.setItem("OrderCart", JSON.stringify(new_data));

        console.log("actr localstorass", new_data);
      }
    }

    $scope.loadData();
    $scope.calculate();
  };

  $scope.guardarOrden = () => {
    jQuery("#btnSaveOrder .title_btn").html("Guardando...");
    jQuery("#btnSaveOrder div.cart-loader").show();

    var image_marca = localStorage.getItem("marca_sel_image");
    var name_marca = localStorage.getItem("name_marca");
    var marca_sel = localStorage.getItem("marca_sel");
    var tienda_sel = localStorage.getItem("tiendaSeleccionada");
    var tienda_name = localStorage.getItem("tienda_name");

    //console.log("dzn", tienda_name)

    var formData = new FormData();

    const files_send = myDropzone.getFiles();

    for (let x = 0; x < files_send.length; x++) {
      formData.append("file_order[]", files_send[x]);
    }

    // formData.append(
    //   "order",
    //   btoa(
    //     JSON.stringify({
    //       items: $scope.items,
    //       total_order: $scope.total_order,
    //       marca: marca_sel,
    //       image_marca: image_marca,
    //       name_marca: name_marca,
    //       tienda: tienda_sel,
    //       tienda_name: localStorage.getItem("tienda_name"),
    //     })
    //   )
    // );
      
    formData.append("items", JSON.stringify($scope.items));
    formData.append("total_order", $scope.total_order);
    formData.append("marca", marca_sel);
    formData.append("image_marca", image_marca);
    formData.append("name_marca", name_marca);
    formData.append("tienda", tienda_sel);
    formData.append("tienda_name", tienda_name);
    


    formData.append("links", JSON.stringify($scope.links));

    if ($scope.orden_id) {
      formData.append("orden_id", localStorage.getItem("orden_id_edit"));
    }

    console.log("to send ", formData);

    // Realizar la solicitud AJAX
    jQuery.ajax({
      url: base_url + "/wp-json/ordenes_cristal/v1/guardar_orden",
      type: "POST",
      data: formData,
      contentType: false,
      processData: false, // Evitar el procesamiento automático de datos
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", nonce);
        //xhr.setRequestHeader( 'Content-Type', 'multipart/form-data; charset=utf-8; boundary=' + Math.random().toString().substr(2) );
      },

      success: function (response) {
        console.log("El archivo se ha subido correctamente.", response);
        // Manejar la respuesta del servidor si es necesario
        jQuery("#btnSaveOrder .title_btn").html("Guardar");
        jQuery("#btnSaveOrder div.cart-loader").hide();
        console.log("svo ", response.data);
        localStorage.removeItem("OrderCart");
        localStorage.removeItem("orden_id_edit");
        localStorage.removeItem("orden_is_send");

        localStorage.removeItem("marca_sel");
        localStorage.removeItem("tienda_name");
        localStorage.removeItem("tienda_sel");

        localStorage.removeItem("marca_sel_image");
        localStorage.removeItem("name_marca");

        Swal.fire(
          `Pedido # ${response.order_id} ha sido guardado, redirigiendo a listado de pedidos...`,
          "",
          "success"
        );

        setTimeout(() => {
          location.href = base_url + "/index.php/listado-de-pedidos/";
        }, 7000);
      },
      error: function (xhr, status, error) {
        console.error("Error al subir el archivo:", error);
        // Manejar el error si es necesario
        jQuery("#btnSaveOrder .title_btn").html("Guardar");
        jQuery("#btnSaveOrder div.cart-loader").hide();
      },
    });
  };

  $scope.clearCart = () => {
    localStorage.removeItem("OrderCart");
    localStorage.removeItem("orden_id_edit");
    localStorage.removeItem("orden_is_send");


    localStorage.removeItem("marca_sel");
    localStorage.removeItem("tienda_name");
    localStorage.removeItem("tienda_sel");

    localStorage.removeItem("marca_sel_image");
    localStorage.removeItem("name_marca");

    jQuery('#main-nav ul li a[title="Pedido"]').hide();
  };

  $scope.NewOrder = () => {
    $scope.clearCart();

    location.href = base_url;
  };

  $scope.setQty = (action, id_item) => {
    console.log("id_item", id_item);
    var data = JSON.parse(localStorage.getItem("OrderCart"));
    var found = data.find((i) => i.ID == id_item);

    if (action == "add") {
      found.cnt = parseFloat(found.cnt) + 1;
    } else {
      found.cnt = parseFloat(found.cnt) - 1;
    }

    if (found.cnt <= 0) {
      $scope.removeItem(id_item);
      return false;
    }

    found.subtotal = parseInt(found.price) * parseFloat(found.cnt);
    var new_data = data.filter((i) => i.ID != id_item);
    new_data.push(found);
    console.log("nwdta_rp", new_data);

    localStorage.setItem("OrderCart", JSON.stringify(new_data));

    console.log("update qty", new_data);
    $scope.items = new_data;
    $scope.loadData();
    $scope.calculate();
  };

  // Función para generar un nuevo ID único para un producto
  $scope.generateNewProductId = function (productList) {
    var maxId = 0;

    // Iterar sobre la lista de productos para encontrar el máximo ID
    productList.forEach(function (product) {
      if (product.ID > maxId) {
        maxId = product.ID;
      }
    });

    // Incrementar el máximo ID en 1 para obtener un nuevo ID único
    var newId = maxId + 1;
    return newId;
  };

  // Función para agregar un nuevo producto a la orden
  $scope.addNewProduct = function (formData) {
    // Agregar el nuevo producto a localStorage
    console.log(formData);

    // Generar un nuevo ID único para el nuevo producto
    var newProductId = $scope.generateNewProductId($scope.items);

    var newProduct = {
      ID: newProductId,
      sku: formData.sku,
      post_title: formData.post_title,
      post_content: formData.post_content,
      cnt: parseInt(formData.cnt),
      price: parseInt(formData.price),
      categorias: [
        categorias_data.find((c) => c.term_id == formData.categorias),
      ],

      observacion: formData.observacion,
      sku: formData.sku,
      image_url:
        plugins_url + "/ordenes_cristal/assets/img/product-placeholder.png",
      subtotal: parseInt(formData.price) * parseInt(formData.cnt),
    };

    console.log(newProduct);

    // Verificar si ya hay productos en el carrito
    var cartData = JSON.parse(localStorage.getItem("OrderCart")) || [];
    // Agregar el nuevo producto al carrito
    cartData.push(newProduct);
    // Actualizar localStorage con los nuevos datos del carrito
    localStorage.setItem("OrderCart", JSON.stringify(cartData));

    // Recargar los datos del carrito
    $scope.get_valores_ideales_order();
    $scope.calculate();
  };

  $scope.marcaSeleccionada = "";
  $scope.categoriaSeleccionada = "";
  $scope.nombreProducto = "";
  $scope.tiendaSeleccionada = "";

  $scope.buscarProductos = function () {
    var parametros = {};
    if ($scope.marcaSeleccionada) {
      parametros.marca = $scope.marcaSeleccionada;
    }
    if ($scope.categoriaSeleccionada) {
      parametros.categoria = $scope.categoriaSeleccionada;
    }
    if ($scope.nombreProducto) {
      parametros.nombre = $scope.nombreProducto;
    }
    if ($scope.tiendaSeleccionada) {
      parametros.tienda = $scope.tiendaSeleccionada;
    }

    // Lógica para realizar la búsqueda de productos con los parámetros seleccionados
    $http({
      method: "POST",
      url: base_url + "/wp-json/ordenes_cristal/v1/buscar_productos",
      params: parametros,
      headers: { "X-WP-Nonce": nonce },
    }).then(function (response) {
      // Manejar la respuesta de la búsqueda de productos
      console.log("Tienda ", response.data.tienda);
      $scope.sresult = response.data.productos;
      $scope.smarca = response.data.marca;
      $scope.stienda = response.data.tienda;
    });
  };
});

function showMiniCart() {
  // Esperar a que AngularJS inicialice el elemento MiniCart
  angular.element(document).ready(function () {
    // Obtener el alcance de AngularJS del elemento MiniCart
    var scope = angular.element(document.getElementById("MiniCart")).scope();

    // Aplicar los cambios en el alcance
    scope.$apply(function () {
      // Llamar a la función loadData en el controlador correspondiente para actualizar los datos del mini carrito
      scope.loadData();
      scope.calculate();
    });
  });

  // Mostrar el mini carrito
  jQuery("#cartResume").fadeIn();
  jQuery("#cartResume div.card").animate({
    width: "25%",
    left: "75%",
  });
}

function hideMiniCart() {
  jQuery("#cartResume").fadeOut();
  jQuery("#cartResume div.card").animate({
    width: "0%",
    left: "100%",
  });
}

jQuery(document).ready(function ($) {
  $("#login-form").submit(function (e) {
    e.preventDefault();

    var formData = $(this).serialize();

    $.ajax({
      type: "POST",
      url: admin_ajax_url,
      data: formData + "&action=custom_login",
      success: function (response) {
        if (response.success) {
          $("#login-error").html("Inicio de sesión exitoso");
          // Aquí puedes redirigir a otra página si es necesario
          location.href = base_url;
        } else {
          $("#login-error").html(response.data.message);
        }
      },
      error: function (xhr, status, error) {
        $("#login-error").html(xhr.responseText);
      },
    });
  });

  $(".obs-toggle-l").click(function (e) {
    $("#order-list-table")
      .find("i.fa-caret-down")
      .removeClass("fa-caret-down")
      .addClass("fa-caret-up");
    $("#order-list-table").find(".detail-order").hide();

    $(this).find("i").removeClass("fa-caret-up").addClass("fa-caret-down");
    $(this).closest("tbody").find(".detail-order").show();
  });

  $(document).on("click", ".obs-toggle", function () {
    console.log("press enter");
    var $icon = $(this).find("i");

    if ($icon.hasClass("fa-caret-down")) {
      console.log("Show observ_field");
      // Ocultar todos los observ_field
      $("#table-cart-items tbody tr td")
        .find("i.fa-caret-up")
        .removeClass("fa-caret-up")
        .addClass("fa-caret-down");
      $("#table-cart-items tbody tr td").find(".observ_field").hide();

      // Mostrar el observ_field correspondiente
      $icon.removeClass("fa-caret-down").addClass("fa-caret-up");
      $(this).closest("tr").find(".observ_field").show();
    } else {
      console.log("Hide observ_field");
      // Ocultar el observ_field correspondiente
      $icon.removeClass("fa-caret-up").addClass("fa-caret-down");
      $(this).closest("tr").find(".observ_field").hide();
    }
  });
  // Capturar el clic en el botón de editar orden
  $(".edit_order").click(function () {
    var orderId = $(this).data("id_orden");
    // Realizar una solicitud AJAX para obtener los datos de la orden
    $.ajax({
      url: admin_ajax_url,
      type: "GET",
      data: "id_orden=" + orderId + "&action=my_get_order",
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", nonce);
      },
      success: function (response) {
        // Manejar la respuesta exitosa
        console.log("Datos de la orden:", response.data);
        // Almacenar los datos de la orden en el localStorage

        console.log("items ", response.data.items_orden);

        // Almacenar la marca seleccionada en el localStorage
        localStorage.setItem("marca_sel", response.data.orden_data.marca);
        // Almacenar el nombre de la marca seleccionada en el localStorage
        localStorage.setItem("name_marca", response.data.orden_data.name_marca);
        // Almacenar los datos de la tienda seleccionada en el localStorage
        localStorage.setItem(
          "tiendaSeleccionada",
          response.data.orden_data.tienda
        );
        localStorage.setItem("orden_id_edit", response.data.orden_data.id);
        localStorage.setItem("orden_is_send", response.data.orden_data.is_send);

        var localitems = [];
        response.data.items_orden.map((item) => {
          var formData = {
            ID: item.ID,
            sku: item.sku,
            post_title: item.post_title,
            post_content: item.post_content,
            cnt: parseInt(item.cnt),
            categorias: JSON.parse(item.categorias),
            marca: parseInt(item.marca),
            price: parseInt(item.price),
            observacion: item.observacion,
            subtotal: parseInt(item.subtotal),
            image_url: item.image_url,
          };
          console.log("item ", formData);
          localitems.push(formData);
        });

        localStorage.setItem("OrderCart", JSON.stringify(localitems));

        location.href = base_url + "/index.php/hacer_pedido";
      },
      error: function (xhr, status, error) {
        // Manejar el error
        console.error("Error al obtener los datos de la orden:", error);
      },
    });
  });
});

jQuery(document).ready(function ($) {
  // Agrega reglas de validación
  $("#prdForm").validate({
    errorPlacement: function (error, element) {
      error.insertAfter(element);
    },
    rules: {
      sku: "required",
      post_title: "required",
      post_content: "required",
      categorias: "required",
      cnt: "required",
      price: "required",
    },
    messages: {
      sku: "Por favor, ingrese el código",
      post_title: "Por favor, ingrese el nombre",
      post_content: "Por favor, ingrese la descripción",
      categorias: "Por favor, seleccione una categoría",
      cnt: "Por favor, ingrese la cantidad",
      price: "Por favor, ingrese el precio",
    },
  });
});
// Función para validar y enviar el formulario
function AddNewProduct() {
  // Validar el formulario usando jQuery Validate
  if (jQuery("#prdForm").valid()) {
    // Obtener los datos del formulario
    var formData = {
      sku: jQuery("#prdForm input[name='sku']").val(),
      post_title: jQuery("#prdForm input[name='post_title']").val(),
      post_content: jQuery("#prdForm textarea[name='post_content']").val(),
      cnt: jQuery("#prdForm input[name='cnt']").val(),
      categorias: jQuery("#prdForm select[name='categorias']").val(),
      marca: jQuery("#prdForm select[name='marca']").val(),
      price: jQuery("#prdForm input[name='price']").val(),
      observacion: jQuery("#prdForm textarea[name='observacion']").val(),
    };

    // Llamar a la función addNewProduct en el controlador correspondiente
    // Primero, obten el alcance de AngularJS del controlador
    var scope = angular
      .element(document.getElementById("cartController"))
      .scope();
    // Aplica los cambios en el alcance y llama a la función addNewProduct
    scope.$apply(function () {
      scope.addNewProduct(formData);
    });

    // Opcional: Cerrar el modal después de enviar el formulario
    jQuery("#modalAddProduct").modal("hide");
  }
}

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      jQuery(".image-upload-wrap").hide();

      jQuery(".file-upload-image").attr("src", e.target.result);
      jQuery(".file-upload-content").show();

      jQuery(".image-title").html(input.files[0].name);
    };

    angular.element(document).ready(function () {
      // Actualizar el valor de $scope.file_order_upload en el controlador de AngularJS
      var scope_cart = angular
        .element(document.getElementById("cartController"))
        .scope();
      console.log(scope_cart);
      scope_cart.$apply(() => {
        scope_cart.file_order_upload = input.files[0];
        console.log(scope_cart.file_order_upload);
      });
    });

    reader.readAsDataURL(input.files[0]);
  } else {
    removeUpload();
  }
}

function removeUpload() {
  jQuery(".file-upload-input").replaceWith(
    jQuery(".file-upload-input").clone()
  );
  jQuery(".file-upload-content").hide();
  jQuery(".image-upload-wrap").show();
}
jQuery(".image-upload-wrap").bind("dragover", function () {
  jQuery(".image-upload-wrap").addClass("image-dropping");
});
jQuery(".image-upload-wrap").bind("dragleave", function () {
  jQuery(".image-upload-wrap").removeClass("image-dropping");
});

///dropzone

if (jQuery("#box").length > 0) {
  var myDropzone = new FileDropzone({
    target: "#box",
    fileHoverClass: "entered",
    clickable: true,
    multiple: true,
    forceReplace: false,
    paramName: "my-file",
    accept: "",
    onChange: function () {
      var files = this.getFiles();
      var elem = jQuery("#files");
      elem.empty();
      files.forEach(function (item) {
        elem.append(
          '<div class="file-name" data-id="' +
            item.id +
            '">' +
            item.name +
            ' <button type="button" class="btn  btn-xs" ng-click="removeFile(' +
            item.id +
            ')"><i class="fa fa-trash" aria-hidden="true"></i></button></div>'
        );
      });

      console.log("files multi", files);

      angular.element(document).ready(function () {
        // Actualizar el valor de $scope.file_order_upload en el controlador de AngularJS
        var scope_cart = angular
          .element(document.getElementById("cartController"))
          .scope();
        console.log(scope_cart);
        scope_cart.$apply(() => {
          scope_cart.file_order_upload = files;
          console.log(scope_cart.file_order_upload);
        });
      });
    },
    onEnter: function () {
      console.log("enter");
    },
    onLeave: function () {
      console.log("leave");
    },
    onHover: function () {
      console.log("hover");
    },
    onDrop: function () {
      console.log("drop");
    },
    onFolderFound: function (folders) {
      console.log(
        "" +
          folders.length +
          " folders ignored. Change noFolder option to true to accept folders."
      );
    },
    onInvalid: function (files) {
      console.log("file invalid");
      console.log(files);
    },
    beforeAdd: function (files) {
      for (var i = 0, len = files.length; i < len; i++) {
        let file = files[i];
        file.id = new Date().getTime();
        if (/fuck/.test(file.name)) {
          return false;
        }
      }
      return true;
    },
  });
}

const addTocart = (id_item, e) => {
  console.log("adding", e);
  var qty = 1;

  angular.element(document).ready(function () {
    // Actualizar el valor de $scope.file_order_upload en el controlador de AngularJS
    var scope_cart = angular
      .element(document.getElementById("cartController"))
      .scope();
    console.log(scope_cart);
    scope_cart.$apply(() => {
      scope_cart.addCart(id_item, qty);
    });
  });
};

jQuery(document).ready(function ($) {
  $(".popover-descript").popover({
    trigger: "hover",
  });

  $("#btnSendOrder").click(function (e) {
    e.preventDefault();

    $("#btnSendOrder .cart-loader").show();

    const order = localStorage.getItem("orden_id_edit");

    $.ajax({
      type: "POST",
      url: admin_ajax_url,
      data: "action=mail_order&order_id=" + order,
      success: function (response) {
        console.log(response);
        if (response.result == 'success') {
          console.log("Orden sending ");
         
          angular.element(document).ready(function () {
          
            var scope_cart = angular
              .element(document.getElementById("cartController"))
              .scope();

            console.log("Orden send");
            
            scope_cart.$apply(() => {
              scope_cart.orden_is_send = 1
              scope_cart.clearCart();
              Swal.fire(
                `Pedido # ${order} ha sido enviado. Redirigiendo a listado de pedidos...`,
                "",
                "success"
              );
             
            });


          });

          localStorage.setItem("orden_is_send", 1);
          $("#btnSendOrder .cart-loader").hide();
          location.href = base_url + "/listado-de-pedidos/"
        } else {
          $("#btnSendOrder .cart-loader").hide();
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      }
    
    });


  });
});
