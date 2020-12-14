<?php
include ("header.php");
/*
include('vendor/rmccue/requests/library/Requests.php');
Requests::register_autoloader();
$headers = array(
    'Connection' => 'keep-alive',
    'Accept' => 'application/json',
    'X-Requested-With' => 'XMLHttpRequest',
    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36',
    'Content-Type' => 'application/json; charset=UTF-8',
    'Origin' => 'https://www.safekorea.go.kr',
    'Sec-Fetch-Site' => 'same-origin',
    'Sec-Fetch-Mode' => 'cors',
    'Sec-Fetch-Dest' => 'empty',
    'Referer' => 'https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y^&menuSeq=679',
    'Accept-Language' => 'en-US,en;q=0.9,ko-KR;q=0.8,ko;q=0.7,en-GB;q=0.6',
    'Cookie' => 'JSESSIONID=LZcQ6uLKn34lHhbxq-N-1ccux_RRD0PPfgAV486J.safekorea-app-925-vp2zr; e4d3f82b588e6cf132ee4765403cd800=c88c69a8120ee59cc3a71dcb8b80372b; elevisor_for_j2ee_uid=9ypwmptnzxb8y; _ga=GA1.3.1772409697.1607688740; _gid=GA1.3.169785825.1607688740; 3053195ebb01870828aed202f55dc974=8544559e4b22e33cdbd84b6f037e83ce; bbs_read_63_72386=Y; bbs_read_63_72394=Y; _gat_gtag_UA_136232158_1=1'
);
$data = '^{^\\^bbs_searchInfo^\\^:^{^\\^pageIndex^\\^:^\\^1^\\^,^\\^pageUnit^\\^:^\\^10^\\^,^\\^pageSize^\\^:^\\^10^\\^,^\\^firstIndex^\\^:^\\^1^\\^,^\\^lastIndex^\\^:^\\^1^\\^,^\\^recordCountPerPage^\\^:^\\^10^\\^,^\\^bbs_no^\\^:^\\^63^\\^,^\\^bbs_ordr^\\^:^\\^^\\^,^\\^use^\\^:^\\^^\\^,^\\^opCode^\\^:^\\^^\\^,^\\^search_type_v^\\^:^\\^^\\^,^\\^search_val_v^\\^:^\\^^\\^,^\\^search_key_n^\\^:^\\^^\\^,^\\^search_notice^\\^:^\\^^\\^,^\\^search_use^\\^:^\\^^\\^,^\\^search_permits^\\^:^\\^^\\^,^\\^search_disaster_a^\\^:^\\^^\\^,^\\^search_disaster_b^\\^:^\\^^\\^,^\\^search_amendment^\\^:^\\^^\\^,^\\^search_start^\\^:^\\^20201112^\\^,^\\^search_end^\\^:^\\^20201212^\\^,^\\^search_date_limit^\\^:^\\^20201112^\\^^}^}';
$response = Requests::post('https://www.safekorea.go.kr/idsiSFK/bbs/user/selectBbsList.do', $headers, $data);
*/
?>

<!--
<script>
//var url = 'https://www.safekorea.go.kr/idsiSFK/bbs/user/selectBbsList.do'; //A local page
var url = 'https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y&menuSeq=679'; //A local page

function load(url, callback) {
  var xhr = new XMLHttpRequest();

  xhr.onreadystatechange = function() {
    if (xhr.readyState === 4) {
      callback(xhr.response);
    }
  }

  xhr.open('GET', url, true);
  xhr.send('');
}
</script>
-->
<!--
<script>

//**************************************************************************
//DataMap
//**************************************************************************
var neoSafeKoreaDataMapList = [

		//****************************************
		// bbs_searchInfo
		//****************************************
		{
			id : "bbs_searchInfo",
			data : {
				  pageIndex          : "1"
				, pageUnit           : "10"
				, pageSize           : "10"
				, firstIndex         : "1"
				, lastIndex          : "1"
				, recordCountPerPage : "10"
				, bbs_no             : ""
				, bbs_ordr           : ""
				, use                : ""
				, opCode             : ""
				, search_type_v      : ""
				, search_val_v       : ""
				, search_key_n       : ""
				, search_notice      : ""
				, search_use         : ""
				, search_permits     : ""
				, search_disaster_a  : ""
				, search_disaster_b  : ""
				, search_amendment   : ""
				, search_start       : ""
				, search_end         : ""
				//, search_date_limit  : ""
			}
		}
];

//**************************************************************************
//DataList
//**************************************************************************
var neoSafeKoreaSubDataListList = [
		{ id:"bbsList" }
];


//**************************************************************************
//Submission
//**************************************************************************
var neoSafeKoreaSubSubmissionList = [

		//******************************************************************
		// getBbsSelect_submission
		//******************************************************************
		{
			  id         : "getBbsSelect_submission"
			, processMsg  : "조회중입니다..."
			, submitdone : getBbsSelect_submitdone
			, mode       : "asynchronous"
			, ref        : {"id":"bbs_searchInfo","key":"bbs_searchInfo"}
			, target     : [{"id":"bbsList","key":"bbsList"},{"id":"dm_resultMap","key":"rtnResult"}]
			, action     : "/idsiSFK/bbs/user/selectBbsList.do"
			, encoding   : "UTF-8"
			, method     : "post"
			, mediatype  : "application/json"
		}
];


$(function(){
	safeKoreaEngineInitializeDataMap(neoSafeKoreaDataMapList);
	safeKoreaEngineInitializeDataList(neoSafeKoreaSubDataListList);
	safeKoreaEngineInitializeSubmission(neoSafeKoreaSubSubmissionList);
});

//**************************************************************************
//
//**************************************************************************

	var bbs_no = null;

	$(function(){
		bbs_no = '63';
		initPage();
	});

	//초기화
	function initPage(){
		NeoSafeKoreaGenerator.call(document.getElementById("bbs_tr"));
		var toDay = DateUtil.getToday();

		bbs_searchInfo.set("bbs_no", bbs_no);
		bbs_searchInfo.set("pageUnit", "10");
		bbs_searchInfo.set("search_date_limit", DateUtil.addMonth(toDay.replace(/-/g, ""), -1));

		search_start.setValue(bbs_searchInfo.get("search_date_limit"));
		search_end.setValue(toDay.replace(/-/g, ""));

		bbs_searchInfo.set("search_start", bbs_searchInfo.get("search_date_limit"));
		bbs_searchInfo.set("search_end", toDay.replace(/-/g, ""));

		var listYn = sessionStorage.getItem("listYn");
		if(listYn == "list") {
			sessionStorage.setItem("listYn", "");

			bbs_searchInfo.set("search_type_v", sessionStorage.getItem("search_type_v"));
			bbs_searchInfo.set("search_val_v", sessionStorage.getItem("search_val_v"));

			search_type_v.setValue(sessionStorage.getItem("search_type_v"));
			search_val_v.setValue(sessionStorage.getItem("search_val_v"));

			bbs_searchInfo.set("search_start", sessionStorage.getItem("searchDate1"));
			bbs_searchInfo.set("search_end", sessionStorage.getItem("searchDate2"));

			search_start.setValue(sessionStorage.getItem("searchDate1"));
			search_end.setValue(sessionStorage.getItem("searchDate2"));

			bbs_searchInfo.set("pageIndex", sessionStorage.getItem("pageIndex"));

			minPage.setValue( bbs_searchInfo.get( "pageIndex" ) );
			bbs_page.setValue( bbs_searchInfo.get( "pageIndex" ) );
		}

		getBbsSelect_submission.exec();
	}

	//리스트 콜백
	function getBbsSelect_submitdone(e) {
		var pageSizeChk = parseInt(dm_resultMap.get( "pageSize" )) ;
		var i = parseInt(bbs_page.getValue());

		if(0 < i && i <= pageSizeChk){
			bbs_searchInfo.set("pageIndex", i);
			minPage.setValue(i);
		}
		else{
			bbs_searchInfo.set("pageIndex", 1);
			bbs_page.setValue("1");
		}

		// 페이징 시작
		bbs_searchInfo.set( "pageSize" , dm_resultMap.get( "pageSize" ) );
		minPage.setValue( bbs_searchInfo.get( "pageIndex" ) );
		maxPage.setValue( bbs_searchInfo.get( "pageSize" ) );
		totCnt.setValue( dm_resultMap.get( "totCnt" ));
		// 페이징 끝

		bbs_tr.removeAll();

		if(bbsList.getRowCount() > 0){
			//데이터 리스트
			for(var i =0 ; i < bbsList.getRowCount() ; i ++){
				var index_tr = bbs_tr.insert();

				$("[id^=bbs_tr_]").removeAttr("g_index");

				bbs_tr.getChild(index_tr, "apiData1").setStyle( "display" , "" );
				bbs_tr.getChild(index_tr, "apiData2").setStyle( "display" , "none" );

				bbs_tr.getChild(index_tr, "num").setStyle( "display" , "" );
				bbs_tr.getChild(index_tr, "num_td").setValue(bbsList.getCellData( i , "NUM" ));

				bbs_tr.getChild(index_tr, "sj").setStyle( "display" , "" );
				var bbs_href= "javascript:bbsDtl(\'"+bbsList.getCellData( i , "BBS_NO" ) +"\',\'"+bbsList.getCellData( i , "BBS_ORDR" ) +"\');";
				bbs_tr.getChild(index_tr, "bbs_title").setHref(bbs_href);

				bbs_tr.getChild(index_tr, "bbs_title").setValue( bbsList.getCellData( i , "SJ" ) );

        		var today1 = new Date();
				var today2 = new Date(bbsList.getCellData( i , "FRST_REGIST_DT"));
				today1.setDate(today1.getDate() - 0);
				if(today1 <= today2){
					bbs_tr.getChild(index_tr, "bbs_new").setStyle( "display" , "" );
				}

				bbs_tr.getChild(index_tr, "usr_nm").setStyle( "display" , "" );
				bbs_tr.getChild(index_tr, "usr_nm_td").setValue(bbsList.getCellData( i , "USR_NM" ));

				bbs_tr.getChild(index_tr, "frst_regist_dt").setStyle( "display" , "" );
				bbs_tr.getChild(index_tr, "frst_regist_dt_td").setValue(bbsList.getCellData( i , "FRST_REGIST_DT" ));

//				bbs_tr.getChild(index_tr, "qry_cnt").setStyle( "display" , "" );
//				bbs_tr.getChild(index_tr, "qry_cnt_td").setValue(bbsList.getCellData( i , "QRY_CNT" ));
			}
		}
		else{
			var index_tr = bbs_tr.insert();

			$("[id^=bbs_tr_]").removeAttr("g_index");

			bbs_tr.getChild(index_tr, "apiData1").setStyle( "display" , "none" );
			bbs_tr.getChild(index_tr, "apiData2").setStyle( "display" , "" );
		}
	};

	//상세 페이지 이동
	function bbsDtl(bbs_no, bbs_ordr) {
		var path = "/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgView.jsp?menuSeq=679";

		sessionStorage.setItem("bbs_no", bbs_no);
		sessionStorage.setItem("bbs_ordr", bbs_ordr);
		sessionStorage.setItem("opCode", "2");

		sessionStorage.setItem("search_type_v", bbs_searchInfo.get("search_type_v"));
		sessionStorage.setItem("search_val_v", bbs_searchInfo.get("search_val_v"));

		sessionStorage.setItem("searchDate1", search_start.getValue());
		sessionStorage.setItem("searchDate2", search_end.getValue());
		sessionStorage.setItem("pageIndex",   bbs_searchInfo.get("pageIndex"));

		location.href = path;
	}

	//검색
	function fn_search() {
		if(!CommonUtil.isNull(search_start.getValue()) && !CommonUtil.isNull(search_end.getValue())){
			//날짜 전후 비교
			if(!valLib.compareDate( search_start.getValue() , search_end.getValue() , 4, "시작일자", "종료일자" )){
				search_start.focus();
				return false;
			}
			/*if(search_start.getValue() < bbs_searchInfo.get("search_date_limit")) {
				alert("최대 1개월 전까지 검색이 가능합니다.");
				search_start.focus();
				return false;
			}*/
			if(DateUtil.getDaysDiff(search_start.getValue(), search_end.getValue()) > 31) {
				alert("최대 30일 까지 검색이 가능합니다.");
				search_start.focus();
				return false;
			}
		}

		bbs_searchInfo.set("pageIndex" , "1");
		bbs_searchInfo.set("search_val_v", search_val_v.getValue());
		bbs_searchInfo.set("search_type_v", search_type_v.getValue());
		getBbsSelect_submission.exec();
	}

	function search_val_v_onkeydown(e) {
		if(event.keyCode == 13){
			fn_search();
		}
	};

	function sDateValid(){
		var word = search_start.getValue();
		var d1   = toDateFull(-1);
		if(word == ""){
		}
		else if(!(DateUtil.checkDate(word))){
			alert('날짜형식이 맞지 않습니다.');
			search_start.setValue(d1);
			return false;
		}

		bbs_searchInfo.set("search_start", word);
	}

	function eDateValid(){
		var word = search_end.getValue();
		var d2   = toDateFull(0);
		if(word == ""){
		}
		else if(!(DateUtil.checkDate(word))){
			alert('날짜형식이 맞지 않습니다.');
			search_end.setValue(d2);
			return false;
		}

		bbs_searchInfo.set("search_end", word);
	}

	function toDateFull(n) {
		// 날짜 계산하기(박성호) 20160829_익스플로어 구 버전에서는 dateUtil이 잘 안됨.
		var d = new Date();
		var year=d.getFullYear();
		var month=(d.getMonth()+1+n);
		var day=d.getDate();

		if(month > 12 ){
			month = month-12;
			year = year+1;
		}

		if(String(month).length==1)
			month="0"+month;
		if(String(day).length==1)
			day="0"+day;
		var s = String(year)+String(month)+String(day);
		return s;
	}

	function onSearchBtnClick(){
		bbs_searchInfo.set("pageIndex", 1);
		fn_search();
	}

	function onPrevPageBtnClick(){
		if(bbs_searchInfo.get("pageIndex") > 1){
			var pageIndex = Number(bbs_searchInfo.get("pageIndex")) ;
			bbs_searchInfo.set("pageIndex" , pageIndex - 1);
			bbs_page.setValue( pageIndex-1 );
		}
		getBbsSelect_submission.exec();
	}

	function onNextPageBtnClick(){
		if(bbs_searchInfo.get("pageIndex") < dm_resultMap.get( "pageSize" )){
			var pageIndex = Number(bbs_searchInfo.get("pageIndex")) ;
			bbs_searchInfo.set("pageIndex" , pageIndex + 1);
			bbs_page.setValue( pageIndex+1 );
		}
		getBbsSelect_submission.exec();
	}

	function onGoToPageBtnClick(){
		var pageSize = parseInt(dm_resultMap.get( "pageSize" )) ;
		var i = parseInt(bbs_page.getValue());
		if((i<=0)|| (i > pageSize)){
			alert("해당 페이지는 존재하지 않습니다.");
			bbs_page.setValue("");
			return;
		}

		bbs_searchInfo.set( "pageIndex",i );
		getBbsSelect_submission.exec();
	}
</script>
-->

<?php
// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y^&menuSeq=679');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "^{^^bbs_searchInfo^^\":^{^^\"pageIndex^^\":^^\"1^^\",^^\"pageUnit^^\":^^\"10^^\",^^\"pageSize^^\":^^\"10^^\",^^\"firstIndex^^\":^^\"1^^\",^^\"lastIndex^^\":^^\"1^^\",^^\"recordCountPerPage^^\":^^\"10^^\",^^\"bbs_no^^\":^^\"63^^\",^^\"bbs_ordr^^\":^^\"^^\",^^\"use^^\":^^\"^^\",^^\"opCode^^\":^^\"^^\",^^\"search_type_v^^\":^^\"^^\",^^\"search_val_v^^\":^^\"^^\",^^\"search_key_n^^\":^^\"^^\",^^\"search_notice^^\":^^\"^^\",^^\"search_use^^\":^^\"^^\",^^\"search_permits^^\":^^\"^^\",^^\"search_disaster_a^^\":^^\"^^\",^^\"search_disaster_b^^\":^^\"^^\",^^\"search_amendment^^\":^^\"^^\",^^\"search_start^^\":^^\"20201112^^\",^^\"search_end^^\":^^\"20201212^^\",^^\"search_date_limit^^\":^^\"20201112^^\"^}^}\"");
curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

$headers = array();
$headers[] = 'Connection: keep-alive';
$headers[] = 'Cache-Control: no-cache';
$headers[] = 'Upgrade-Insecure-Requests: 1';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36';
$headers[] = 'Accept: image/avif,image/webp,image/apng,image/*,*/*;q=0.8';
$headers[] = 'Sec-Fetch-Site: same-origin';
$headers[] = 'Sec-Fetch-Mode: no-cors';
$headers[] = 'Sec-Fetch-User: ?1';
$headers[] = 'Sec-Fetch-Dest: image';
$headers[] = 'Accept-Language: en-US,en;q=0.9,ko-KR;q=0.8,ko;q=0.7,en-GB;q=0.6';
$headers[] = 'Cookie: JSESSIONID=LZcQ6uLKn34lHhbxq-N-1ccux_RRD0PPfgAV486J.safekorea-app-925-vp2zr; e4d3f82b588e6cf132ee4765403cd800=c88c69a8120ee59cc3a71dcb8b80372b; elevisor_for_j2ee_uid=9ypwmptnzxb8y; _ga=GA1.3.1772409697.1607688740; _gid=GA1.3.169785825.1607688740; 3053195ebb01870828aed202f55dc974=8544559e4b22e33cdbd84b6f037e83ce; bbs_read_63_72386=Y; bbs_read_63_72394=Y; _gat_gtag_UA_136232158_1=1';
$headers[] = 'Referer: https://www.safekorea.go.kr/idsiSFK/neo/sfk/cs/sfc/dis/disasterMsgList.jsp?emgPage=Y^&menuSeq=679';
$headers[] = 'Authority: www.google-analytics.com';
$headers[] = 'If-Modified-Since: Sat, 12 Dec 2020 12:00:00 GMT';
$headers[] = 'X-Requested-With: XMLHttpRequest';
$headers[] = 'Content-Type: text/plain';
$headers[] = 'Origin: https://www.safekorea.go.kr';
$headers[] = 'Content-Length: 0';
$headers[] = 'Pragma: no-cache';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}

print($result);
curl_close($ch);
?>
