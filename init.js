/*
**************************************
* Copyright Danny Morabito (@newsocialifecom) all right reserved
* You are allowed to use this plugin and edit it as much as you want. 
* Just please leave this small license 
**************************************
*/

(function(global, $){

    var codiad = global.codiad,
        scripts= document.getElementsByTagName('script'),
        path = scripts[scripts.length-1].src.split('?')[0],
        curpath = path.split('/').slice(0, -1).join('/')+'/';

    codiad.htaccess_generator = {
        
        path: curpath,
        
        open: function(action) {
            codiad.modal.load(600, this.path+"generator.php?projectpath="+codiad.project.getCurrent()+"&action="+action);
        },
        delete: function() {
            if(confirm("Are you sure you want to delete the current .htaccess? This action CANNOT be undone.")) 
                this.open("delete");
        },
        prepare_generation: function() {
            var args = Array.prototype.slice.call(arguments);
            args = args.join(" ");
            this.generate(args);
        },
        generate: function(code, file) {
            if(!file)
                file = "htaccess";
            $.get(this.path+"generator.php?action=write&projectpath="+codiad.project.getCurrent()+"&code="+escape(code)+"&file="+file, function() {
                codiad.message.success("Code wrote succefully");
            });
        },
        basic_auth: function() {
            this.prepare_generation("AuthType Basic", "\nAuthName \"authenticate\"", "\nAuthUserFile", codiad.project.getCurrent()+"/.htpasswd", "\nRequire valid-user");
        },
        sendhtpasswd: function(username, password) {
            $.post(this.path+"generator.php?action=password&projectpath="+codiad.project.getCurrent(), {"username": username, "password": password}, function() {
                codiad.message.success("User added succefully"); 
            });
        }
    };
})(this, jQuery);
