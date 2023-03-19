$(()=>{
  // 色の訂正
  if($("#its_game").length){
    $("body,.fixed_top,.backtopdiv").css("background-color",'rgb('+$("body").data("red")+","+$("body").data("green")+","+$("body").data("blue")+')');
    $("body").css("color",colorset());
  }

  if(!$("body.notransbody").length){
    $("body").addClass("bodytrans");
  }

if($(".recordh1").length){
  $(".rank_tr").each((i,elem)=>{
    const red=$(elem).data("r");
    const green=$(elem).data("g");
    const blue=$(elem).data("b");
    
    if ((red * 0.299 + green * 0.587 + blue * 0.114) < 186) {
      $(elem).css("color","white");
    }
  })
}


  function colorset(){
  const color=$("body").css("background-color");
    const firstconma=color.indexOf(",");
    const secondconma=color.indexOf(",",firstconma+1);
    const red=color.substring(4,firstconma);
    const green=color.substring(firstconma+2,secondconma);
    const blue=color.substring(secondconma+2,color.length-1);
    var newcolor = 'black';
    if ((red * 0.299 + green * 0.587 + blue * 0.114) < 186) {
        newcolor = 'white';
        $("table,tr,th,td,.toph2,.user_answer_set,.backtopp").css("border-color","white");
        $(".backtopa").css("color","white");
    }
    return newcolor;
 }


    // inputがfocusされた時、window幅によっては戻るを非表示に
    // どんな場合も共通
    if(screen.width<700){
      
      if($("input").length){
        $("input").each((i,elem)=>{
          $(elem).focus(()=>{
            if($(".backtopdiv").length>0){
              // $(".backtopdiv").css("position","absolute");
              $(".backtopdiv").css("display","none");
            }
          })
           $(elem).blur(()=>{
            if($(".backtopdiv").length>0){
              $(".backtopdiv").css("display","block");
            }
          })
        })
      }
    }



      // 画面が縦に狭く、かつ横の方が長い場合の処理場合
      ifminiscreen();
   
      $(window).resize(function(){
        ifminiscreen();
      })


    function ifminiscreen(){
      if($("#its_game").length>0){
      if(screen.height<500 && window.innerWidth>window.innerHeight+100){
       
        // 空白だとなぜか効かない
          $("body#its_game.bodytrans").css({"transition-property": 'noproperty'});
          
          $(".display_reverse").css("display","block");
          $(".fixed_top").css("display","none");
          $(".not_fixed").css("display","none");
          $(".backtopdiv").css("display","none");
        }else{
          $(".display_reverse").css("display","none");
          $(".fixed_top").css("display","block");
          $(".not_fixed").css("display","block");
          $(".backtopdiv").css("display","block");
        }
    }
  }

  

});