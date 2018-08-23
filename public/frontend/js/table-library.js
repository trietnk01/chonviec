var vMemberTable           =   null;
var vProductTable           =   null;
var vMediaTable           =   null;
var vInvoiceTable           =   null;
var basicTable = function () {    
    var initMemberTable = function () {
        vMemberTable = $('#tbl-member').DataTable({
            columns: [           
                { data: "checked"            },                        
                { data: "fullname"      },
                { data: "email"      },
                { data: "mobilephone"      },
                { data: "address"         },                
            ]
        });        
    };        
    var initProductTable = function () {
        vProductTable = $('#tbl-product').DataTable({
            aLengthMenu: [
                [10, -1],
                [10, "All"]
            ],
            iDisplayLength: -1,
            columns: [                
                { data: "checked"       },                
                { data: "fullname"      },                
                { data: "category_name"         },                            
                { data: "image"         },                
                { data: "status"        },                                
                { data: "edited"        },         
                { data: "deleted"       },                
            ]            
        });        
    };
    var initMediaTable = function () {
        vMediaTable = $('#tbl-media').DataTable({
            aLengthMenu: [
                [10, -1],
                [10, "All"]
            ],
            iDisplayLength: -1,
            columns: [                
                { data: "checked"            },  
                { data: "featured_file"      },                              
                { data: "fullname"      },                
                { data: "deleted"    },                
            ]
        });        
    };
    var initInvoiceTable = function () {
        vInvoiceTable = $('#tbl-invoice').DataTable({
            aLengthMenu: [
                [10, -1],
                [10, "All"]
            ],
            iDisplayLength: -1,
            columns: [                
                { data: "checked"       },               
                { data: "code"      },
                { data: "username"      },   
                { data: "email"         },     
                { data: "fullname"      },
                { data: "created_at"      },                                            
                { data: "status"        },                  
                { data: "edited"        },                                 
            ]
        });        
    };
    return {
        init: function () {
            if (!jQuery().dataTable){
                return;        
            }        
            initMemberTable();    
            initProductTable();  
            initMediaTable();    
            initInvoiceTable();  
        }
    };
}();
