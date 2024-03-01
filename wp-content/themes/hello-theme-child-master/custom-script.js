jQuery(document).ready(function() {
    var coll = document.getElementsByClassName("collapsible");
    console.log(coll);
    var i;
    var textAreas = [];
    
    for (i = 0; i < coll.length; i++) {
      coll[i].addEventListener("click", function() {
        this.classList.toggle("active");
        console.log(this);
        var className = "product-descriptions-"+this.id;
        activateTextArea(className);
        
      });
    }
    function activateTextArea(className){
      var content = document.querySelector(className);
      console.log(content);
      if (content.style.display === "block") {
        content.style.display = "none";
      } else {
        content.style.display = "block";
      }
    }
  })