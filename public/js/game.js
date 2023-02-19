$(()=>{

  if($("#its_game").length){
    $("body,.fixed_top,.backtopdiv").css("background-color",'rgb('+$("body").data("red")+","+$("body").data("green")+","+$("body").data("blue")+')');
    $("body").css("color",colorset());
    console.log($("body").css("background-color"));
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


});