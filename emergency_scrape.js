// var webPage = require('webpage');
// var page = webPage.create();
// page.onLoadFinished = function(status) {
//   console.log('Status: ' + status);
//   // Do other things here...
//   page.render('github.png');
//   //phantom.exit();
// };
//
// //var page = require('webpage').create();
// page.open('https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y&menuSeq=679', function() {
//   //page.render('github.png');
//   phantom.exit();
// });

// var page = require('webpage').create();
// var system = require('system');
//
// page.onInitialized = function() {
//     page.onCallback = function(data) {
//         console.log('Main page is loaded and ready');
//         //Do whatever here
//         page.render('github.png');
//     };
//
//     page.evaluate(function() {
//         document.addEventListener('DOMContentLoaded', function() {
//             window.callPhantom();
//         }, false);
//         console.log("Added listener to wait for page ready");
//     });
//
// };
//
// page.open('https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y&menuSeq=679', function(status) {});

var resourceWait  = 300,
    maxRenderWait = 2000,
    url           = 'https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y&menuSeq=679';

var page          = require('webpage').create(),
    count         = 0,
    forcedRenderTimeout,
    renderTimeout;

page.viewportSize = { width: 1280, height : 1024 };

function doRender() {
    page.render('twitter.png');
    //phantom.exit();
}

function doEvaluate(){
  // var json_text = document.querySelector(".boardList_table").innerHTML,
  //     json_values = JSON.parse(json_text);
  // console.log(json_values);
  //return json_values.skey;

  // var ske = page.evaluate(function() {
  // var json_text = document.getElementsByClassName("boardList_table").innerHTML,
  //     json_values = JSON.parse(json_text);
  //     return json_values.skey;
  // });
  // console.log(ske)

  var title = page.evaluate(function() {
    var coll_arr = Array();
    for(var i=0; i<9; i++){
      var inner_arr = Array();
      //0 --> ID
      //1 --> Date + hr
      //2 --> message detail
      //3 --> location

      var temp = $(document.getElementById("bbs_tr_"+i+"_apiData1")).find("td");
      inner_arr[0] = temp[0].lastElementChild.innerText; //ID
      var master = temp[1].firstElementChild.innerText; //master

      var spl = master.split(" ");
      //var date = new Date(spl[0].replace(/\//g,"-") + "T" + spl[1]);
      //inner_arr[1] = date; // date
      inner_arr[1] = spl[0].replace(/\//g,"-") + "T" + spl[1];
      inner_arr[2] = spl[2].split("[")[0]; //message type

      inner_arr[3] = spl[2].split("[")[1].replace("]", "");

      coll_arr[i] = inner_arr;

      /*var temp = $(document.getElementById("bbs_tr_"+i+"_apiData1")).find("td");
      inner_arr[0] = temp[0].lastElementChild.innerText; //ID
      var master = temp[1].firstElementChild.innerText; //master

      var spl = master.split(" ");
      var date = new Date(spl[0].replace(/\//g,"-") + "T" + spl[1]);
      inner_arr[1] = date; // date
      inner_arr[2] = spl[2].split("[")[0]; //message type

      inner_arr[3] = spl[2].split("[")[1].replace("]", "");

      coll_arr[i] = inner_arr;
      */
    }

    //var temp = document.getElementsByClassName("boardList_table")[0];
    //var coll = $(document.getElementsByClassName("boardList_table")[0]).find("span[id*=bbs_tr_]");
    // var res;
    // coll.forEach(element => {
    //   res += i.innerHTML;
    // });
    //return coll[0].innerHTML;
    return coll_arr;
  });

  console.log(title);

  phantom.exit();
}

page.onResourceRequested = function (req) {
    count += 1;
    console.log('> ' + req.id + ' - ' + req.url);
    clearTimeout(renderTimeout);
};

page.onResourceReceived = function (res) {
    if (!res.stage || res.stage === 'end') {
        count -= 1;
        console.log(res.id + ' ' + res.status + ' - ' + res.url);
        if (count === 0) {
            renderTimeout = setTimeout(doRender, resourceWait);
        }
        // if(res.id == 16){
        //   var toJson = JSON.parse(res);
        //   console.log(toJson);
        // }
    }
};

page.open(url, function (status) {
    if (status !== "success") {
        console.log('Unable to load url');
        phantom.exit();
    } else {
        forcedRenderTimeout = setTimeout(function () {
            console.log(count);
            doRender();
            doEvaluate();
        }, maxRenderWait);
    }
});
