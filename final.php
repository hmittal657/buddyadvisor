
<html>
  <head>
  	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  	<script>

      var types = [
                      
                      "atm",
                      
                      "bank",
                     
                      "cafe",
                      
                      "clothing_store",
                      
                      
                      "gym",
                      
                      "hospital",
                      
                      "library",
                      
                      
                      "park",
                      
                      "restaurant",
                      
                      "shopping_mall",
                      
                      "store",
                      
                      "train_station",
                    
                    ];


  		function distance(a,b){
          var length=((a[0]-b[0])*(a[0]-b[0]))+(a[1]-b[1])*(a[1]-b[1]);
          return length;
      }

      var nearest = 10000000;
      var thisPlaceX=0,thisPlaceY=0;
      var thisPlaceType = "";
      var myPlace = [];

  		function getNearbyPlaces(latitude,longitude,type,index){
  			var url = "https://maps.googleapis.com/maps/api/place/radarsearch/json?location="
            +latitude+","+longitude+"&radius=500&type="+type+"&key=AIzaSyDLSlQykTgmoe5ukRxrW6AAPA-42xp3hPA";


			
	  		$(document).ready(function(){
	  			
	  			$.getJSON(url,function(data){
	  				

	  				for (var i = 0; i < data.results.length; i++) {
	  					var x = latitude-data.results[i].geometry.location.lat;
	  					var y = longitude-data.results[i].geometry.location.lng;
	  					
              if(myPlace[index][2]>x*x+y*y){
                myPlace[index][2] = x*x+y*y;
                thisPlaceX = data.results[i].geometry.location.lat;
                thisPlaceY = data.results[i].geometry.location.lng;
                thisPlaceType = type;
                myPlace[index] = [thisPlaceX,thisPlaceY,myPlace[index][2],type];
                
              }
	  				}

	  				// console.log(index,thisPlaceX,thisPlaceY,myPlace[index][2],thisPlaceType);
            
	  			});

	  		});
  		}


      function getPlaceType(latitude,longitude,index){
        
        for(var type = 0; type<types.length; type++){
          getNearbyPlaces(latitude,longitude,types[type],index);
        }
      }

  		function main(){
  			
        $.getJSON('data.json',function(data){
          console.log(data);
          var k=0;
          var array=[];
          var array2=[];
          for(var i=0;i<100;i++){
              var myLatLng = {lat: data.locations[i].latitudeE7, lng: data.locations[i].longitudeE7};
                        var timestamp = data.locations[i].timestampMs;
                        while(distance(myLatLng,{lat: data.locations[i++].latitudeE7, lng: data.locations[i++].longitudeE7})<1000)
                        {
                          continue;
                        }
              array[k]=timestamp-data.locations[i].timestampMs;
              array2[k]=myLatLng;
              k++;
            }
            console.log(array);
            console.log(array2);

            for (var i = 0; i < array2.length; i++) {
                // console.log("hello",i,array2[i].lat,array2[i].lng);
                myPlace[i] = [0,0,100000000,""];
                getPlaceType(array2[i].lat/10000000,array2[i].lng/10000000,i); 

                
            }
            window.setTimeout(function(){
              for (var i = 0; i < array2.length; i++) {
                          console.log(i,myPlace[i]);
                          
                          
                }

                $.post("trying.php", {variable: myPlace})
			    .done(function(data) {
			        alert("done "+data);
			    });


            }, 10000);

            
        });
  			
  		}

  		main();
  		
  	</script>
  </head>
  <body></body>
</html>