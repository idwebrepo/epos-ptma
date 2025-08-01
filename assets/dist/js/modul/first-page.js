
/* global Chart:false */
var $salesChart = null;
var $cashChart = null;          
var $profitChart = null;          
var $topitemChart = null;          
var tabelExpired = null;
var tabelTerlaris = null;
var tabelTerlarisJasa = null;
var tabelNeraca = null;
var tabelSaldo = null;

$(function () {
  'use strict'

  $.fn.dataTable.ext.errMode = 'none';      

  var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  }

  var mode = 'index';
  var intersect = true;

  $(".content-wrapper").overlayScrollbars({
    className: "os-theme-dark",
    overflowBehavior : {
      x : 'hidden',
      y : 'scroll' 
    },
    scrollbars : {
      autoHide : 'false',
      autoHideDelay : 800,
      snapHandle:true             
    }
  });

  $('.datepicker').datepicker('setDate', 'dd-mm-yyyy');

  $('.datepicker').inputmask({
    alias:'dd/mm/yyyy',
    mask: "1-2-y", 
    placeholder: "_", 
    leapday: "-02-29", 
    separator: "-"
  })

  $('#tipechart1,#tipechart2,#tipechart3,#tipechart4,.select-bulan,#c4jumlah').select2({
     "allowClear": false,
     "theme":"bootstrap4"
  });  

  $('#c1tahun,#c2tahun,#c3tahun,#c4tahun').select2({
     "allowClear": false,
     "theme":"bootstrap4",
     "allowAddLink": true,
     "addLink":"form_periode",      
     "linkTitle":"Periode",                                
     "ajax": {
        "url": base_url+"Select_Master/view_tahun_periode2",
        "type": "post",
        "dataType": "json",                                       
        "delay": 800,
        "data": function(params) {
          return {
            search: params.term
          }
        },
        "processResults": function (data, page) {
        return {
          results: data
        };
      },
    }
  });  

  $("#bwidgetqty").click(function() {
    _loadwidgetsalesqty();      
  });

  $("#bwidgetomset").click(function() {
    _loadwidgetomset();      
  });

  $("#bwidgetlaba").click(function() {
    _loadwidgetlaba();      
  });

  $("#bwidgetsales").click(function(){
    _loadwidgetsales();
  });

  $("#bwidgetcashflow").click(function(){
    _loadwidgetcashflow();
  });

  $("#bwidgetprofit").click(function(){
    _loadwidgetprofit();
  });

  $("#bwidgetbiaya").click(function(){
    _loadwidgetbiaya();
  });

  $("#bwidgettopitem,#topitemfsubmit").click(function(){
    _loadwidgettopitem();
  });

  $(this).on("select2:select", "#tipechart4,#c4tahun", function(){
    _loadwidgettopitem();
  });  

  $(this).on("select2:select", "#tipechart3,#c3tahun", function(){
    _loadwidgetprofit();
  });  

  $(this).on("select2:select", "#tipechart2,#c2tahun", function(){
    _loadwidgetcashflow();
  });  

  $(this).on("select2:select", "#tipechart1,#c1tahun", function(){
    _loadwidgetsales();
  });  

  $('#tgl').datepicker('setDate','dd-mm-yy');       
  var tglTrans = $("#tgl").val();

  var thn = $("<option selected='selected'></option>").val(tglTrans.substr(6,4)).text(tglTrans.substr(6,4));
  $("#c1tahun,#c2tahun,#c3tahun,#c4tahun").append(thn).trigger('change');

  var _renderuserwidget = () => {
    $.ajax({ 
        "url"    : base_url+"Admin_User/getakseswidgetsess",       
        "type"   : "POST", 
        "dataType" : "json", 
        "cache"  : false,
        "success" : function(result) {
            var rows = 0;
            $.each(result.data, function() {                          
              $('#'+result.data[rows]['adnama']).removeClass('d-none');
              rows++;
            });
            return;
        }
    })
  }

  var _loadwidgetsalesqty = () => {
    $.ajax({ 
        "url"    : base_url+"Master_Item/getsalesqty",       
        "type"   : "POST", 
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend": function(){
            $("#day-qty,#month-qty,#year-qty").html('<i class="fas fa-spinner fa-spin"></i>');          
        },
        "success" : function(result) {
            var rows = 0;
            $.each(result.data, function() {   
              switch(result.data[rows]['keterangan']){
                case 'day':
                  $("#day-qty").html(result.data[rows]['qty']);
                  break;
                case 'month':
                  $("#month-qty").html(result.data[rows]['qty']);                
                  break;
                case 'year':
                  $("#year-qty").html(result.data[rows]['qty']);                
                  break;
                default:
                  break;
              }
              rows++;              
            })                       
            return;              
        }
    })
  }

  var _loadwidgetomset = () => {
    $.ajax({ 
        "url"    : base_url+"Master_Item/getomset",       
        "type"   : "POST", 
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend": function(){
            $("#day-omset,#month-omset,#year-omset").html('<i class="fas fa-spinner fa-spin"></i>');          
        },
        "success" : function(result) {
            var rows = 0;
            $.each(result.data, function() {   
              switch(result.data[rows]['keterangan']){
                case 'day':
                  $("#day-omset").html("Rp "+accounting.formatMoney(result.data[rows]['omset']));
                  break;
                case 'month':
                  $("#month-omset").html("Rp "+accounting.formatMoney(result.data[rows]['omset']));
                  break;
                case 'year':
                  $("#year-omset").html("Rp "+accounting.formatMoney(result.data[rows]['omset']));
                  break;
                default:
                  break;
              }
              rows++;              
            })                       
            return;              
        }
    })
  }

  var _loadwidgetbiaya = () => {
    $.ajax({ 
        "url"    : base_url+"Master_Item/getbiaya",       
        "type"   : "POST", 
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend": function(){
            $("#day-biaya,#month-biaya,#year-biaya").html('<i class="fas fa-spinner fa-spin"></i>');          
        },
        "success" : function(result) {
            var rows = 0;
            $.each(result.data, function() {   
              switch(result.data[rows]['keterangan']){
                case 'day':
                  $("#day-biaya").html("Rp "+accounting.formatMoney(result.data[rows]['biaya']));
                  break;
                case 'month':
                  $("#month-biaya").html("Rp "+accounting.formatMoney(result.data[rows]['biaya']));
                  break;
                case 'year':
                  $("#year-biaya").html("Rp "+accounting.formatMoney(result.data[rows]['biaya']));
                  break;
                default:
                  break;
              }
              rows++;              
            })                       
            return;              
        }
    })
  }

  var _loadwidgetlaba = () => {
    $.ajax({ 
        "url"    : base_url+"Master_Item/getlaba",       
        "type"   : "POST", 
        "dataType" : "json", 
        "cache"  : false,
        "beforeSend": function(){
            $("#day-profit,#month-profit,#year-profit").html('<i class="fas fa-spinner fa-spin"></i>');          
        },
        "success" : function(result) {
            var rows = 0;
            $.each(result.data, function() {   
              switch(result.data[rows]['keterangan']){
                case 'day':
                  $("#day-profit").html("Rp "+accounting.formatMoney(result.data[rows]['laba']));
                  break;
                case 'month':
                  $("#month-profit").html("Rp "+accounting.formatMoney(result.data[rows]['laba']));
                  break;
                case 'year':
                  $("#year-profit").html("Rp "+accounting.formatMoney(result.data[rows]['laba']));
                  break;
                default:
                  break;
              }
              rows++;              
            })                       
            return;              
        }
    })
  }

  var _loadwidgetsales = () => {
      $.ajax({ 
        "url"    : base_url+"Chart_Data/sales",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data" : "year="+$("#c1tahun").val(),
        "cache"  : false,
        "beforeSend" : function(){
          let iChartContent = document.getElementById('sales-chart-content');
          iChartContent.innerHTML = '';
          $('#sales-chart-content').append('<canvas id="sales-chart" height="200"></canvas>');          

          $('#load-chart1').removeClass('d-none');
          $('#foot-chart1').removeClass('d-flex');
          $('#foot-chart1').addClass('d-none');                            
        },        
        "error"  : function(xhr,status,error){    
          console.log(xhr.responseText);              
          return;
        },
        "success" : function(result) {
          $('#load-chart1').addClass('d-none');
          $('#foot-chart1').removeClass('d-none');
          $('#foot-chart1').addClass('d-flex');                                              

          var roworder = 0, order1 = 0, order2 = 0, order3 = 0, order4 = 0, order5 = 0,
              order6 = 0, order7 = 0, order8 = 0, order9 = 0, order10 = 0, order11 = 0, order12 = 0;

          var rowsales = 0, sales1 = 0, sales2 = 0, sales3 = 0, sales4 = 0, sales5 = 0,
              sales6 = 0, sales7 = 0, sales8 = 0, sales9 = 0, sales10 = 0, sales11 = 0, sales12 = 0;

          $.each(result.data, function() {
            switch(result.data[roworder]['bulan']){
              case '1':
                order1 = result.data[roworder]['total'];
                break;
              case '2':
                order2 = result.data[roworder]['total'];
                break;                                      
              case '3':
                order3 = result.data[roworder]['total'];
                break;                       
              case '4':
                order4 = result.data[roworder]['total'];
                break;                                                                                 
              case '5':
                order5 = result.data[roworder]['total'];
                break;                                                                                                 
              case '6':
                order6 = result.data[roworder]['total'];
                break;
              case '7':
                order7 = result.data[roworder]['total'];
                break;
              case '8':
                order8 = result.data[roworder]['total'];
                break;
              case '9':
                order9 = result.data[roworder]['total'];
                break;
              case '10':
                order10 = result.data[roworder]['total'];
                break; 
              case '11':
                order11 = result.data[roworder]['total'];
                break; 
              case '12':
                order12 = result.data[roworder]['total'];
                break;
            }                 
            roworder++;
          });          

          $.each(result.data2, function() {
            switch(result.data2[rowsales]['bulan']){
              case '1':
                sales1 = result.data2[rowsales]['total'];
                break;
              case '2':
                sales2 = result.data2[rowsales]['total'];
                break;                                      
              case '3':
                sales3 = result.data2[rowsales]['total'];
                break;                       
              case '4':
                sales4 = result.data2[rowsales]['total'];
                break;                                                                                 
              case '5':
                sales5 = result.data2[rowsales]['total'];
                break;                                                                                                 
              case '6':
                sales6 = result.data2[rowsales]['total'];
                break;
              case '7':
                sales7 = result.data2[rowsales]['total'];
                break;
              case '8':
                sales8 = result.data2[rowsales]['total'];
                break;
              case '9':
                sales9 = result.data2[rowsales]['total'];
                break;
              case '10':
                sales10 = result.data2[rowsales]['total'];
                break; 
              case '11':
                sales11 = result.data2[rowsales]['total'];
                break; 
              case '12':
                sales12 = result.data2[rowsales]['total'];
                break;
            }                 
            rowsales++;
          });          

          var yearsales = Number(sales1)+Number(sales2)+Number(sales3)+Number(sales4)+Number(sales5)+Number(sales6)+Number(sales7)+Number(sales8)+Number(sales9)+Number(sales10)+Number(sales11)+Number(sales12);
          $("#lblyearsales").text('Rp' + accounting.formatMoney(yearsales));

          $salesChart = $("#sales-chart").get(0).getContext("2d");

          if($('#tipechart1').val() !== 'pie'){
            var salesChart = new Chart($salesChart, {
            type: $('#tipechart1').val(),
            data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                {
                  backgroundColor: '#007bff',
                  borderColor: '#007bff',
                  fill: false,
                  data: [sales1, sales2, sales3, sales4, sales5, sales6, sales7, sales8, sales9, sales10, sales11, sales12]
                },
                {
                  backgroundColor: '#ced4da',
                  borderColor: '#ced4da',
                  fill: false,
                  data: [order1, order2, order3, order4, order5, order6, order7, order8, order9, order10, order11, order12]
                }
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  // display: false,
                  gridLines: {
                    display: true,
                    lineWidth: '4px',
                    color: 'rgba(0, 0, 0, .2)',
                    zeroLineColor: 'transparent'
                  },
                  ticks: $.extend({
                    beginAtZero: true,
                    callback: function (value) {
                      if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                      }
                      return value
                    }
                  }, ticksStyle)
                }],
                xAxes: [{
                  display: true,
                  gridLines: {
                    display: true
                  },
                  ticks: ticksStyle
                }]
              }
            }
          });
          } else {
           $('#foot-chart1').removeClass('d-flex');            
           $('#foot-chart1').addClass('d-none');
           var salesChart = new Chart($salesChart, {
            type: $('#tipechart1').val(),
            data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                {
                  backgroundColor: ['#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da'],
                  borderColor: ['#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da'],                  
                  data: [sales1, sales2, sales3, sales4, sales5, sales6, sales7, sales8, sales9, sales10, sales11, sales12]
                }
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: true
              }
            }            
           });
          }
        } 
      })
  }

  var _loadwidgetcashflow = () => {
      $.ajax({ 
        "url"    : base_url+"Chart_Data/kasbank",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data" : "year="+$("#c2tahun").val(),
        "cache"  : false,
        "beforeSend" : function(){
          let iChartContent = document.getElementById('cashflow-chart-content');
          iChartContent.innerHTML = '';
          $('#cashflow-chart-content').append('<canvas id="cashflow-chart" height="200"></canvas>');          

          $('#load-chart2').removeClass('d-none');        
          $('#foot-chart2').removeClass('d-flex');
          $('#foot-chart2').addClass('d-none');                                      
        },        
        "error"  : function(xhr,status,error){                  
          console.log(xhr.responseText);              
          return;
        },
        "success" : function(result) {
          $('#load-chart2').addClass('d-none');        
          $('#foot-chart2').removeClass('d-none');
          $('#foot-chart2').addClass('d-flex');                            

          var rowkas = 0, kas1 = 0, kas2 = 0, kas3 = 0, kas4 = 0, kas5 = 0,
              kas6 = 0, kas7 = 0, kas8 = 0, kas9 = 0, kas10 = 0, kas11 = 0, kas12 = 0;

          var rowbank = 0, bank1 = 0, bank2 = 0, bank3 = 0, bank4 = 0, bank5 = 0,
              bank6 = 0, bank7 = 0, bank8 = 0, bank9 = 0, bank10 = 0, bank11 = 0, bank12 = 0;

          $.each(result.data, function() {
            switch(result.data[rowkas]['bulan']){
              case '1':
                kas1 = result.data[rowkas]['total'];
                break;
              case '2':
                kas2 = result.data[rowkas]['total'];
                break;                                      
              case '3':
                kas3 = result.data[rowkas]['total'];
                break;                       
              case '4':
                kas4 = result.data[rowkas]['total'];
                break;                                                                                 
              case '5':
                kas5 = result.data[rowkas]['total'];
                break;                                                                                                 
              case '6':
                kas6 = result.data[rowkas]['total'];
                break;
              case '7':
                kas7 = result.data[rowkas]['total'];
                break;
              case '8':
                kas8 = result.data[rowkas]['total'];
                break;
              case '9':
                kas9 = result.data[rowkas]['total'];
                break;
              case '10':
                kas10 = result.data[rowkas]['total'];
                break; 
              case '11':
                kas11 = result.data[rowkas]['total'];
                break; 
              case '12':
                kas12 = result.data[rowkas]['total'];
                break;
            }                 
            rowkas++;
          });          

          $.each(result.data2, function() {
            switch(result.data2[rowbank]['bulan']){
              case '1':
                bank1 = result.data2[rowbank]['total'];
                break;
              case '2':
                bank2 = result.data2[rowbank]['total'];
                break;                                      
              case '3':
                bank3 = result.data2[rowbank]['total'];
                break;                       
              case '4':
                bank4 = result.data2[rowbank]['total'];
                break;                                                                                 
              case '5':
                bank5 = result.data2[rowbank]['total'];
                break;                                                                                                 
              case '6':
                bank6 = result.data2[rowbank]['total'];
                break;
              case '7':
                bank7 = result.data2[rowbank]['total'];
                break;
              case '8':
                bank8 = result.data2[rowbank]['total'];
                break;
              case '9':
                bank9 = result.data2[rowbank]['total'];
                break;
              case '10':
                bank10 = result.data2[rowbank]['total'];
                break; 
              case '11':
                bank11 = result.data2[rowbank]['total'];
                break; 
              case '12':
                bank12 = result.data2[rowbank]['total'];
                break;
            }                 
            rowbank++;
          });          

          $cashChart = $("#cashflow-chart").get(0).getContext("2d");

          var cashChart = new Chart($cashChart, {
            type: $('#tipechart2').val(),
            data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                {
                  backgroundColor: '#007bff',
                  borderColor: '#007bff',
                  fill: false,
                  data: [kas1, kas2, kas3, kas4, kas5, kas6, kas7, kas8, kas9, kas10, kas11, kas12]
                },
                {
                  backgroundColor: '#ced4da',
                  borderColor: '#ced4da',
                  fill: false,
                  data: [bank1, bank2, bank3, bank4, bank5, bank6, bank7, bank8, bank9, bank10, bank11, bank12]
                }
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  // display: false,
                  gridLines: {
                    display: true,
                    lineWidth: '4px',
                    color: 'rgba(0, 0, 0, .2)',
                    zeroLineColor: 'transparent'
                  },
                  ticks: $.extend({
                    beginAtZero: true,
                    callback: function (value) {
                      if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                      }
                      return value
                    }
                  }, ticksStyle)
                }],
                xAxes: [{
                  display: true,
                  gridLines: {
                    display: true
                  },
                  ticks: ticksStyle
                }]
              }
            }
          });
        } 
      })
  }

  var _loadwidgetprofit = () => {
      $.ajax({ 
        "url"    : base_url+"Chart_Data/profitloss",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data" : "year="+$("#c3tahun").val(),
        "cache"  : false,
        "beforeSend" : function(){
          let iChartContent = document.getElementById('profit-chart-content');
          iChartContent.innerHTML = '';
          $('#profit-chart-content').append('<canvas id="profit-chart" height="200"></canvas>');          

          $('#load-chart3').removeClass('d-none');
          $('#foot-chart3').removeClass('d-flex');
          $('#foot-chart3').addClass('d-none');                            
        },        
        "error"  : function(xhr,status,error){    
          console.log(xhr.responseText);              
          return;
        },
        "success" : function(result) {
          $('#load-chart3').addClass('d-none');
          $('#foot-chart3').removeClass('d-none');
          $('#foot-chart3').addClass('d-flex');                                              

          var row1 = 0, val1 = 0, val2 = 0, val3 = 0, val4 = 0, val5 = 0,
              val6 = 0, val7 = 0, val8 = 0, val9 = 0, val10 = 0, val11 = 0, val12 = 0;

          var row2 = 0, valb1 = 0, valb2 = 0, valb3 = 0, valb4 = 0, valb5 = 0,
              valb6 = 0, valb7 = 0, valb8 = 0, valb9 = 0, valb10 = 0, valb11 = 0, valb12 = 0;

          var row3 = 0, valc1 = 0, valc2 = 0, valc3 = 0, valc4 = 0, valc5 = 0,
              valc6 = 0, valc7 = 0, valc8 = 0, valc9 = 0, valc10 = 0, valc11 = 0, valc12 = 0;

          $.each(result.data, function() {
            switch(result.data[row1]['bulan']){
              case '1':
                val1 = result.data[row1]['total']*-1;
                break;
              case '2':
                val2 = result.data[row1]['total']*-1;
                break;                                      
              case '3':
                val3 = result.data[row1]['total']*-1;
                break;                       
              case '4':
                val4 = result.data[row1]['total']*-1;
                break;                                                                                 
              case '5':
                val5 = result.data[row1]['total']*-1;
                break;                                                                                                 
              case '6':
                val6 = result.data[row1]['total']*-1;
                break;
              case '7':
                val7 = result.data[row1]['total']*-1;
                break;
              case '8':
                val8 = result.data[row1]['total']*-1;
                break;
              case '9':
                val9 = result.data[row1]['total']*-1;
                break;
              case '10':
                val10 = result.data[row1]['total']*-1;
                break; 
              case '11':
                val11 = result.data[row1]['total']*-1;
                break; 
              case '12':
                val12 = result.data[row1]['total']*-1;
                break;
            }                 
            row1++;
          });          

          $.each(result.data2, function() {
            switch(result.data2[row2]['bulan']){
              case '1':
                valb1 = result.data2[row2]['total'];
                break;
              case '2':
                valb2 = result.data2[row2]['total'];
                break;                                      
              case '3':
                valb3 = result.data2[row2]['total'];
                break;                       
              case '4':
                valb4 = result.data2[row2]['total'];
                break;                                                                                 
              case '5':
                valb5 = result.data2[row2]['total'];
                break;                                                                                                 
              case '6':
                valb6 = result.data2[row2]['total'];
                break;
              case '7':
                valb7 = result.data2[row2]['total'];
                break;
              case '8':
                valb8 = result.data2[row2]['total'];
                break;
              case '9':
                valb9 = result.data2[row2]['total'];
                break;
              case '10':
                valb10 = result.data2[row2]['total'];
                break; 
              case '11':
                valb11 = result.data2[row2]['total'];
                break; 
              case '12':
                valb12 = result.data2[row2]['total'];
                break;
            }                 
            row2++;
          });          

          $.each(result.data3, function() {
            switch(result.data3[row3]['bulan']){
              case '1':
                valc1 = result.data3[row3]['total'];
                break;
              case '2':
                valc2 = result.data3[row3]['total'];
                break;                                      
              case '3':
                valc3 = result.data3[row3]['total'];
                break;                       
              case '4':
                valc4 = result.data3[row3]['total'];
                break;                                                                                 
              case '5':
                valc5 = result.data3[row3]['total'];
                break;                                                                                                 
              case '6':
                valc6 = result.data3[row3]['total'];
                break;
              case '7':
                valc7 = result.data3[row3]['total'];
                break;
              case '8':
                valc8 = result.data3[row3]['total'];
                break;
              case '9':
                valc9 = result.data3[row3]['total'];
                break;
              case '10':
                valc10 = result.data3[row3]['total'];
                break; 
              case '11':
                valc11 = result.data3[row3]['total'];
                break; 
              case '12':
                valc12 = result.data3[row3]['total'];
                break;
            }                 
            row3++;
          });          

          var labarugi = Number(val1) + Number(val2) + Number(val3) + Number(val4) + Number(val5) + Number(val6) + Number(val7) + Number(val8) + Number(val9) + Number(val10) + Number(val11) + Number(val12);
          labarugi = labarugi - Number(valb1) - Number(valb2) - Number(valb3) - Number(valb4) - Number(valb5) - Number(valb6) - Number(valb7) - Number(valb8) - Number(valb9) - Number(valb10) - Number(valb11) - Number(valb12);
          labarugi = labarugi - Number(valc1) - Number(valc2) - Number(valc3) - Number(valc4) - Number(valc5) - Number(valc6) - Number(valc7) - Number(valc8) - Number(valc9) - Number(valc10) - Number(valc11) - Number(valc12);

          $("#lblyearprofit").text('Rp' + accounting.formatMoney(labarugi));

          $profitChart = $("#profit-chart").get(0).getContext("2d");

          if($('#tipechart3').val() != 'pie'){
          var profitChart = new Chart($profitChart, {
            type: $('#tipechart3').val(),
            data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                {
                  backgroundColor: '#007bff',
                  borderColor: '#007bff',
                  fill: false,
                  data: [val1, val2, val3, val4, val5, val6, val7, val8, val9, val10, val11, val12]
                },
                {
                  backgroundColor: '#17a2b8',
                  borderColor: '#17a2b8',
                  fill: false,
                  data: [valb1, valb2, valb3, valb4, valb5, valb6, valb7, valb8, valb9, valb10, valb11, valb12]
                },                
                {
                  backgroundColor: '#ced4da',
                  borderColor: '#ced4da',
                  fill: false,
                  data: [valc1, valc2, valc3, valc4, valc5, valc6, valc7, valc8, valc9, valc10, valc11, valc12]
                }
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  // display: false,
                  gridLines: {
                    display: true,
                    lineWidth: '4px',
                    color: 'rgba(0, 0, 0, .2)',
                    zeroLineColor: 'transparent'
                  },
                  ticks: $.extend({
                    beginAtZero: true,
                    callback: function (value) {
                      if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                      }
                      return value
                    }
                  }, ticksStyle)
                }],
                xAxes: [{
                  display: true,
                  gridLines: {
                    display: true
                  },
                  ticks: ticksStyle
                }]
              }
            }
          });
        } else {
          try{
            var valp1 = Number(val1) - Number(valb1) - Number(valc1), 
                valp2 = Number(val2) - Number(valb2) - Number(valc2), 
                valp3 = Number(val3) - Number(valb3) - Number(valc3), 
                valp4 = Number(val4) - Number(valb4) - Number(valc4), 
                valp5 = Number(val5) - Number(valb5) - Number(valc5),
                valp6 = Number(val6) - Number(valb6) - Number(valc6), 
                valp7 = Number(val7) - Number(valb7) - Number(valc7), 
                valp8 = Number(val8) - Number(valb8) - Number(valc8), 
                valp9 = Number(val9) - Number(valb9) - Number(valc9), 
                valp10 = Number(val10) - Number(valb10) - Number(valc10), 
                valp11 = Number(val11) - Number(valb11) - Number(valc11), 
                valp12 = Number(val12) - Number(valb12) - Number(valc12);
              } catch (err){
                console.log(err);
              }

           $('#foot-chart3').removeClass('d-flex');            
           $('#foot-chart3').addClass('d-none');
           var profitChart = new Chart($profitChart, {
            type: $('#tipechart3').val(),
            data: {
              labels: ['JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
              datasets: [
                {
                  backgroundColor: ['#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da'],
                  borderColor: ['#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da','#007bff','#ced4da'],                  
                  data: [valp1, valp2, valp3, valp4, valp5, valp6, valp7, valp8, valp9, valp10, valp11, valp12]
                }
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: true
              }
            }            
           });
        }
      } 
      })
  }

  var _loadwidgettopitem = () => {
      $.ajax({ 
        "url"    : base_url+"Chart_Data/topitem",       
        "type"   : "POST", 
        "dataType" : "json", 
        "data" : "year="+$("#c4tahun").val()+"&jumlah="+$("#c4jumlah").val()+"&sbulan="+$("#c4bulan1").val()+"&ebulan="+$("#c4bulan2").val(),
        "cache"  : false,
        "beforeSend" : function(){
          let iChartContent = document.getElementById('topitem-chart-content');
          iChartContent.innerHTML = '';
          $('#topitem-chart-content').append('<canvas id="topitem-chart" height="200"></canvas>');          

          $('#load-chart4').removeClass('d-none');        
        },        
        "error"  : function(xhr,status,error){                  
          console.log(xhr.responseText);              
          return;
        },
        "success" : function(result) {
          $('#load-chart4').addClass('d-none');        
          $('#topitemket').html(`${$("#c4jumlah").val()} Item Barang Terlaris (${$("#c4bulan1").select2('data')[0].text} - ${$("#c4bulan2").select2('data')[0].text})`);

          var row = 0, nitem = [], vitem = [], citem = [];

          $.each(result.data, function() {
            nitem.push(
                result.data[row]['item']
                   );
            vitem.push(
                result.data[row]['jumlah']
                   );

            if(row % 2 == 0) {
              citem.push('#007bff');              
            } else {
              citem.push('#ced4da');                            
            }

            row++;
          });          

          $topitemChart = $("#topitem-chart").get(0).getContext("2d");

          var topitemChart = new Chart($topitemChart, {
            type: $('#tipechart4').val(),
            data: {
              labels: nitem,
              datasets: [
                {
                  backgroundColor: citem,
                  borderColor: citem,                  
                  fill: false,
                  data: vitem
                }
              ]
            },
            options: {
              maintainAspectRatio: false,
              tooltips: {
                mode: mode,
                intersect: intersect
              },
              hover: {
                mode: mode,
                intersect: intersect
              },
              legend: {
                display: false
              },
              scales: {
                yAxes: [{
                  // display: false,
                  gridLines: {
                    display: true,
                    lineWidth: '4px',
                    color: 'rgba(0, 0, 0, .2)',
                    zeroLineColor: 'transparent'
                  },
                  ticks: $.extend({
                    beginAtZero: true,
                    callback: function (value) {
                      if (value >= 1000) {
                        value /= 1000
                        value += 'k'
                      }
                      return value
                    }
                  }, ticksStyle)
                }],
                xAxes: [{
                  display: true,
                  gridLines: {
                    display: true
                  },
                  ticks: ticksStyle
                }]
              }
            }
          });
        } 
      })
  }

  tabelTerlaris=$('#terlaris-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",    
    "order": [[ 4, 'desc' ]],
    "select":true,      
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi/view_item_terlaris",
        "type":"post",
        "data": (data) => {
          data.dari = $('#tgl1terlaris').val();
          data.sampai = $('#tgl2terlaris').val();          
        }                                  
    },
    "deferRender": true,
    "bInfo":true,    
    "aLengthMenu": [[10, 10, 10],[10, 10, 10]],
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },            
    "columns": [
          { "data": "id" },    
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },              
          { "data": "sku" },
          { "data": "nama" },
          { "data": "qty" },          
    ],
    "columnDefs": [
      {
          targets: 4,
          className: 'text-left'
      }
    ],    
    "drawCallback": function() {
      var total = tabelTerlaris.data().count();
      $(".dataTables_processing").removeClass("d-none");   
    }                
  });  

  tabelTerlarisJasa=$('#terlarisjasa-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",    
    "order": [[ 4, 'desc' ]],
    "select":true,      
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi/view_jasa_terlaris",
        "type":"post",
        "data": (data) => {
          data.dari = $('#tgl1jasaterlaris').val();
          data.sampai = $('#tgl2jasaterlaris').val();          
        }                                          
    },
    "deferRender": true,
    "bInfo":true,    
    "aLengthMenu": [[10, 10, 10],[10, 10, 10]],
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },            
    "columns": [
          { "data": "id" },    
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },              
          { "data": "sku" },
          { "data": "nama" },
          { "data": "qty" },          
    ],
    "columnDefs": [
      {
          targets: 4,
          className: 'text-left'
      }
    ],    
    "drawCallback": function() {
      var total = tabelTerlarisJasa.data().count();
      $(".dataTables_processing").removeClass("d-none");   
    }                
  });  

  tabelExpired=$('#expired-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": true,
    "pagingType":"simple",    
    "order": [[ 4, 'asc' ]],
    "select":true,      
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi/view_expired_1_month",
        "type":"post"
    },
    "deferRender": true,
    "bInfo":true,    
    "aLengthMenu": [[10, 10, 10],[10, 10, 10]],
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },            
    "columns": [
          { "data": "id" },    
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },              
          { "data": "sku" },
          { "data": "nama" },
          { "data": "tanggal" },          
          { "data": "qty" }                    
    ],
    "drawCallback": function() {
      var total = tabelExpired.data().count();
      $(".dataTables_processing").removeClass("d-none");   
    }                
  });  

  tabelSaldo=$('#saldoakun-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "pagingType":"simple",    
    "order": [[ 0, 'asc' ]],
    "select":true,      
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi/view_saldo_akun_dasbor",
        "type":"post"
    },
    "deferRender": true,
    "bInfo":true,    
    "aLengthMenu": datapage,
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },            
    "columns": [
          { "data": "id" },    
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },              
          { "data": "keterangan" },
          { "data": "total", 
            "className": 'text-right',          
            "render": (data, type, row,meta) => {
              if (row.DK == 'K') {
                  data = accounting.formatMoney(data*-1);
                  return data;                                    
              } else {
                  data = accounting.formatMoney(data);
                  return data;
              }
            }           
          }
    ],
    "drawCallback": function() {
      var total = tabelSaldo.data().count();
      $(".dataTables_processing").removeClass("d-none");   
    }                
  });  

  tabelNeraca=$('#neraca-table').DataTable({
    "processing": true,
    "serverSide": true,
    "lengthChange": false,
    "searching": false,
    "ordering": false,
    "pagingType":"simple",    
    "order": [[ 0, 'asc' ]],
    "select":true,      
    "dom": '<"top"pi>tr<"clear">',
    "ajax": {
        "url":base_url+"Datatable_Transaksi/view_posisi_keuangan",
        "type":"post"
    },
    "deferRender": true,
    "bInfo":true,    
    "aLengthMenu": datapage,
    "language": 
    {          
      "processing": "<i class='fas fa-circle-notch fa-spin text-primary'></i>",
    },            
    "columns": [
          { "data": "id" },    
          {
          orderable:      false,
          data:           null,
          defaultContent: "<i class='fas fa-caret-right text-sm'></i>"
          },              
          { "data": "keterangan" },
          { "data": "total", 
            "className": 'text-right',          
            "render": (data, type, row,meta) => {
              if (row.DK == 'K') {
                  data = accounting.formatMoney(data*-1);
                  return data;                                    
              } else {
                  data = accounting.formatMoney(data);
                  return data;
              }
            }           
          }
    ],
    "drawCallback": function() {
      var total = tabelNeraca.data().count();
      $(".dataTables_processing").removeClass("d-none");   
    }                
  });  

  $(".dataTables_processing").addClass("d-none");   
  $(".top").addClass("d-none");                          

  $("#bwidgetneraca").click(function() {
    $('#neraca-table').DataTable().ajax.reload();  
  });

  $("#bwidgetsaldoakun").click(function() {
    $('#saldoakun-table').DataTable().ajax.reload();  
  });

  $("#bwidgetexpired").click(function() {
    $('#expired-table').DataTable().ajax.reload();  
  });

  $("#bwidgetterlaris,#barangterlarissubmit").click(function() {
    $('#terlaris-table').DataTable().ajax.reload();  
  });  

  $("#bwidgetterlarisjasa,#jasaterlarissubmit").click(function() {
    $('#terlarisjasa-table').DataTable().ajax.reload();  
  });    

  $("#btgl1terlaris").click(function() {
    $("#tgl1terlaris").focus();
  })

  $("#btgl2terlaris").click(function() {
    $("#tgl2terlaris").focus();
  })  

  $("#btgl1jasaterlaris").click(function() {
    $("#tgl1jasaterlaris").focus();
  })

  $("#btgl2jasaterlaris").click(function() {
    $("#tgl2jasaterlaris").focus();
  })  

  _renderuserwidget();
  _loadwidgetsales();
  _loadwidgetcashflow();
  _loadwidgetprofit();  
  _loadwidgettopitem();  
  _loadwidgetsalesqty();  
  _loadwidgetomset();
  _loadwidgetlaba();
  _loadwidgetbiaya();
})