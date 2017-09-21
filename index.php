<?php
include("config.php");
 ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Diaporama Pixabay</title>
    <link rel="stylesheet" href="css/themes/jqm_astro4twitch_18_09_17.min.css" />
    <link rel="stylesheet" href="css/themes/jquery.mobile.icons.min.css" />
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile.structure-1.4.5.min.css" />
    <link rel="stylesheet" href="css/mon_style.css" />
    <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>


    <script>
      var ma_clee_pixabay = '<?php echo MACLEEPIXABAY; ?>';
      var slideshowtime;
      var interval = 180000;
      var image_affiche = 0;

      $(document).ready(function(){

        console.log("ma page en cours "+$.mobile.pageContainer.pagecontainer( "getActivePage" ).attr( 'id' ));
        //
        $("#validation").on("click", function(){
           rechercher($("#ma_recherche").val());
           console.log("valeur recherchée "+$("#ma_recherche").val());
        });
        //
        $(document).keypress(function(e) {
            if(e.which == 13) {
                if($.mobile.pageContainer.pagecontainer( "getActivePage" ).attr( 'id' )=="accueil"){
                  rechercher($("#ma_recherche").val());
                  console.log("valeur recherchée "+$("#ma_recherche").val());
                }
            }
        });

          function rechercher(sujet){
                var API_KEY = ma_clee_pixabay;
                var URL = "https://pixabay.com/api/?key="+API_KEY;
                  URL += "&q="+encodeURIComponent(sujet);
                  URL += "&safesearch=true";
                  URL += "&per_page=80";
                  URL += "&image_type=photo";
                  URL += "&orientation=horizontal";

                $.getJSON(URL, function(data){
                if (parseInt(data.totalHits) > 0){
                    affichage("diaporama", sujet, data.hits);
                }else{
                    var mon_retour = "Il n'a pas d'image concernant : "+sujet;
                    affichage("diaporama", mon_retour, false);
                }
            });


          }

          function affichage(page, sujet, liens){

            $.mobile.changePage('#'+page,
             {transition : "pop",
              changeHash : false
            });
            $("#mon_resultat").text("valeur de la recherche :"+sujet);
            if(liens){
                $("#ma_listview_container").html("");
                $("#ma_listview_container").append("<ul id='ma_listview' data-role='listview' data-split-icon='delete' data-split-theme='a' data-inset='true'></ul>");
                $("#ma_listview_container").trigger("create");
                $("#ma_listview").listview("refresh");
                $.each(liens, function(i, hit){
                      var listItem = "<li><a href='#' id='afficher_img_"+i+"' value='"+i+"' value2='"+hit.webformatURL+"'><img src='";
                          listItem += hit.previewURL+"'>";
                          listItem += "<h2>"+hit.user+"</h2>";
                          listItem += "<p>"+hit.tags+"</p>";
                //          listItem += "<a href='"+hit.webformatURL+"' data-rel='popup' data-position-to='window' data-transition='pop'>Charger image</a>";
                          listItem += "<a href='' id='supprimer_img_"+i+"'>Supprimer l image</a>";

                      $("#ma_listview").append(listItem);
                });
                $("#ma_listview").listview("refresh");
                $("[id^=supprimer_img_]").on("click", function(){
                      $(this).parent().remove();
                });
                $("[id^=afficher_img_]").on("click", function(){
                      console.log($(this).attr('value'));
                      $("#image").css( 'background-image', 'url(' + $(this).attr('value2') + ')' );
                      $("#image").css( 'background-repeat', 'no-repeat' );
                      $("#image").css( 'background-position', 'center center' );
                      $("#image").css( 'background-attachment', 'fixed' );
                      $("#image").css( 'background-size', 'cover' );


                      $.mobile.changePage('#image',
                       {transition : "pop",
                        changeHash : false
                      });
                });
                image_affiche = 1;
                $("#afficher").on("click", function(){
                    $.mobile.changePage('#image',
                     {transition : "pop",
                      changeHash : false
                    });
                    slideshow(0);
                    slideshowtime = setInterval( function() {
                          slideshow(image_affiche);
                     }, interval );
                });


            }

          }

        $("#retour").on("click", function(){
            $.mobile.changePage('#accueil',
             {transition : "fade",
              changeHash : false
            });
        });
        $("#retour2").on("click", function(){
            clearTimeout( slideshowtime );
            $.mobile.changePage('#diaporama',
             {transition : "fade",
              changeHash : false
            });
        });
        function slideshow(numero){

          console.log("afficher le numero "+numero);
          $("#image").css( 'background-image', 'url(' + $("#afficher_img_"+numero).attr('value2') + ')' );
          $("#image").css( 'background-repeat', 'no-repeat' );
          $("#image").css( 'background-position', 'center center' );
          $("#image").css( 'background-attachment', 'fixed' );
          $("#image").css( 'background-size', 'cover' );

          image_affiche++;
          if(image_affiche == $('#ma_listview li').size() - $('#ma_listview li.ui-screen-hidden').size()){

            image_affiche=0;
          }
        }



      });


    </script>

  </head>
  <body>
      <div data-role="page" id="accueil" data-theme="a">
              <div data-role="header">
                  <h1>Diaporama sur Pixabay</h1>
              </div>
              <div data-role="content">
                  <div class="ui-grid-a">
                      <div class="ui-block-a">
                        <input type="text" id="ma_recherche" placeholder="Entrez le sujet du diaporama">
                      </div>
                      <div class="ui-block-b">
                        <a href="#" id="validation" class="ui-btn ui-shadow" data-theme="a">Valider</a>
                      </div>
                  </div>
              </div>
              <div data-role="footer">
                    <p class="a_centrer">
                      <a href="https://pixabay.com/"><img src="https://pixabay.com/static/img/public/leaderboard_a.png" alt="Pixabay"></a>
                    </p>
             </div>
      </div>
      <div data-role="page" id="diaporama">
          <div data-role="content">
              <div class="ui-grid-c">
                  <div class="ui-block-a">
                      <p id="mon_resultat"></p>
                  </div>
                  <div class="ui-block-b">
                      <a href="#" id="retour" data-role="button" data-icon="back" data-theme="e">retour</a>
                  </div>
                  <div class="ui-block-c">
                      <a href="#" id="afficher" data-role="button" data-icon="check" data-theme="f">Démarrer le diaporama</a>
                  </div>
                  <div class="ui-block-d">
                    <a href="https://pixabay.com/"><img src="https://pixabay.com/static/img/public/leaderboard_a.png" alt="Pixabay"></a>
                  </div>
              </div>
              <div id="ma_listview_container"></div>

          </div>
      </div>
      <div data-role="page" id="image">
        <div data-role="header">
          <a href="#" id="retour2" data-role="button" data-icon="back" data-theme="e">retour</a>
        </div>
        <div data-role="content">


        </div>
      </div>


  </body>
</html>
