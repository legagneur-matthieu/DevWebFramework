$(document).ready(function () {
    var editor = null;
    var editor_type = null;
    var file = null;
    require.config({paths: {vs: '../commun/src/js/monaco/min/vs'}});
    require(['vs/editor/editor.main'], function () {
        function create_editor(value, language = "php") {
            if (editor != null) {
                editor.dispose();
            }
            editor = monaco.editor.create(document.getElementById('monaco_editor'), {
                value: value,
                language: language,
                autoIndent: true,
                formatOnPaste: true,
                formatOnType: true
            });
            editor_type = language;
        }
        function monaco_save() {
            if (editor && editor.getValue() != "") {
                if (file === null) {
                    fullpath = prompt("chemain et nom du fichier", "./class/newfile.php");
                    if (path !== null) {
                        filename = basename(fullpath);
                        file = {
                            fullpath: fullpath,
                            id: sha1(fullpath),
                            file: filename,
                            ext: end(explode(".", filename)),
                            path: strtr(fullpath, "/" + filename, "")
                        };
                        monaco_save_request();
                    }
                } else {
                    monaco_save_request();
                }
            }
        }
        function monaco_save_request() {
            $.get("./services/index.php?service=s_monaco_editor&action=write&file=" + btoa(file.fullpath) +
                    "&data=" + base64_encode(htmlspecialchars(editor.getValue())), function (data) {
                (data.error ? alertify.error(data.msg) : alertify.success(data.msg));
            }, "json");
        }
        function ext2language(ext) {
            let lang = {
                "js": "javascript",
                "ts": "typescript",
                "sh": "shell",
                "bat": "shell",
                "md": "markdown",
                "markdown": "markdown"
            };
            return (isset(lang[ext]) ? lang[ext] : ext);
        }
        create_editor("", "php");
        window.onresize = function () {
            if (editor) {
                editor.layout();
            }
        };
        function open_file(e) {
            e.preventDefault();
            monaco_save();
            if ($(this).attr("data-monaco-ext") != "DIR") {
                file = {
                    id: $(this).attr("data-monaco-id"),
                    file: $(this).attr("data-monaco-file"),
                    path: $(this).attr("data-monaco-path"),
                    fullpath: $(this).attr("data-monaco-fullpath"),
                    ext: $(this).attr("data-monaco-ext")
                };
                $.get("./services/index.php?service=s_monaco_editor&action=read&file=" + btoa(file.fullpath), function (data) {
                    create_editor(data.data, ext2language(file.ext));
                }, "json");
            }
        }
        $("#monaco_files a[data-monaco-file]").click(open_file);
        $("#monaco_files .monaco_files_addfile").click(function () {
            let filename = prompt("nom du fichier ?");
            if (filename != null) {
                if (contexted_object.ext == "DIR") {
                    $.get("./services/index.php?service=s_monaco_editor&action=addfile&path=" + btoa(contexted_object.fullpath) + "&name=" + btoa(filename), function (data) {
                        (data.error ? alertify.error(data.msg) : alertify.success(data.msg));
                        if (!data.error) {
                            let ext = explode(".", filename).at(-1);
                            let html = '<li><a href="#/" data-monaco-id="' + sha1(contexted_object.fullpath + '/' + filename) + '" data-monaco-file="' + filename + '" data-monaco-ext="' + ext + '" data-monaco-path="' + contexted_object.fullpath + '" data-monaco-fullpath="' + contexted_object.fullpath + '/' + filename + '"><span class="bi bi-filetype-' + ext + '"><span class="visually-hidden">&nbsp;</span></span> <span class="monaco_editor_files_name">' + filename + '</span></a></li>';
                            add_node(html);
                            $("a[data-monaco-id='" + sha1(contexted_object.fullpath + '/' + filename) + "']").bind("contextmenu", contextmenu).bind("click", open_file);
                        }
                    }, "json");
                } else {
                    $.get("./services/index.php?service=s_monaco_editor&action=addfile&path=" + btoa(contexted_object.path) + "&name=" + btoa(filename), function (data) {
                        (data.error ? alertify.error(data.msg) : alertify.success(data.msg));
                        if (!data.error) {
                            let ext = explode(".", filename).at(-1);
                            let html = '<li><a href="#/" data-monaco-id="' + sha1(contexted_object.path + '/' + filename) + '" data-monaco-file="' + filename + '" data-monaco-ext="' + ext + '" data-monaco-path="' + contexted_object.path + '" data-monaco-fullpath="' + contexted_object.path + '/' + filename + '"><span class="bi bi-filetype-' + ext + '"><span class="visually-hidden">&nbsp;</span></span> <span class="monaco_editor_files_name">' + filename + '</span></a></li>';
                            add_node(html);
                            $("a[data-monaco-id='" + sha1(contexted_object.path + '/' + filename) + "']").bind("contextmenu", contextmenu).bind("click", open_file);
                        }
                    }, "json");
                }
            }
        });
        //button save
        $("#monaco_editor_save").click(function () {
            monaco_save();
        });
        //button format
        $("#monaco_editor_format").click(function () {
            if (editor_type == "php") {
                $.get("./services/index.php?service=s_monaco_editor&action=format&code=" +
                        base64_encode(htmlspecialchars(editor.getValue())), function (data) {
                    if (data.data) {
                        editor.setValue(data.data);
                    }
                }, "json");
            } else {
                editor.getAction('editor.action.formatDocument').run();
            }
        });
        //file explorer
        $("#monaco_files ul li .bi-folder").parent("a").parent("li").addClass("monaco_files_dir");
        $("li.monaco_files_dir").click(function () {
            $(this).next("li").toggle();
        });
        $("li.monaco_files_dir").click();
        //contextmenu
        $(".monaco_files_contextmenu").hide();
        var contexted_object = {};
        function contextmenu(e) {
            e.preventDefault();
            contexted_object = {
                id: $(this).attr("data-monaco-id"),
                ext: $(this).attr("data-monaco-ext"),
                path: $(this).attr("data-monaco-path"),
                fullpath: $(this).attr("data-monaco-fullpath"),
            }
            if (contexted_object.ext != "DIR") {
                contexted_object.file = $(this).attr("data-monaco-file");
            }
            $(".monaco_files_contextmenu").css("top", (e.pageY - window.scrollY) + "px").css("left", (e.pageX - window.scrollX) + "px").show();

        }
        function get_fullpath_list() {
            let test = [];
            if (contexted_object.ext == "DIR") {
                $("a[data-monaco-id='" + contexted_object.id + "']").parent("li").next().children("ul").children("li").children("a[data-monaco-fullpath]").each(function (k, v) {
                    if ($(this).attr("data-monaco-ext") == "DIR") {
                        test.push($(v).attr("data-monaco-fullpath"));
                    }
                    test.push($(v).attr("data-monaco-fullpath"));
                });
            } else {
                $("a[data-monaco-id='" + contexted_object.id + "']").parent("li").parent("ul").children("li").children("a[data-monaco-fullpath]").each(function (k, v) {
                    if ($(this).attr("data-monaco-ext") == "DIR") {
                        test.push($(v).attr("data-monaco-fullpath"));
                    }
                    test.push($(v).attr("data-monaco-fullpath"));
                });
            }
            return test;
        }
        function get_index_in_alphabet(word, wordlist) {
            wordlist.push(word);
            wordlist.sort();
            return array_search(word, wordlist);
        }
        function add_node(html) {
            let new_fullpath = $(html).children("a").attr("data-monaco-fullpath");
            let list = get_fullpath_list()
            let index = get_index_in_alphabet(new_fullpath, list);
            if (list.length == 1 && contexted_object.ext == "DIR") {
                $("a[data-monaco-id='" + contexted_object.id + "']").parent("li").next().children("ul").append(html);
            } else {
                if (index == list.length - 1) {
                    $("a[data-monaco-fullpath='" + list[0] + "']").parent("li").parent("ul").append(html);
                } else {
                    let i = parseInt(index) + 1;
                    $(html).insertBefore("a[data-monaco-fullpath='" + list[i] + "']:parent");
                }
            }
        }
        $("#monaco_files ul li a").contextmenu(contextmenu);
        $("#monaco_files .monaco_files_adddir").click(function (e) {
            let name = prompt("Entrer le nom du dossier")
            if (contexted_object.ext != "DIR") {
                $.get("./services/index.php?service=s_monaco_editor&action=adddir&path=" + btoa(contexted_object.path) + "&name=" + btoa(name),
                        function (data) {
                            if (!data.error) {
                                let html = "<li class='monaco_file_dir'><a href='#/' data-monaco-id=" + sha1(contexted_object.path + '/' + name) + " data-monaco-file=" + contexted_object.path + "/" + name + " data-monaco-ext='DIR' data-monaco-path=" + contexted_object.path + " data-monaco-fullpath=" + contexted_object.path + "/" + name + "><span class='bi bi-folder'><span class='visually-hidden'>&nbsp;</span></span> <span class='monaco_editor_files_name'>" + name + "</span></a></li><li style='display: none;'><ul class='list-unstyled'></ul></li>";
                                add_node(html);
                                $("a[data-monaco-id='" + sha1(contexted_object.path + '/' + name) + "']").bind("contextmenu", contextmenu).click(function () {
                                    $(this).parent().next().toggle();
                                });
                                $(" a[data-monaco-id='" + contexted_object.id + "']").parent().unbind().click(function () {
                                    $(this).parent().next().toggle();
                                });
                            }
                        }, "json");
            } else {
                $.get("./services/index.php?service=s_monaco_editor&action=adddir&path=" + btoa(contexted_object.fullpath) + "&name=" + btoa(name),
                        function (data) {
                            if (!data.error) {
                                let html = "<li class='monaco_file_dir'><a href='#/' data-monaco-id=" + sha1(contexted_object.fullpath + '/' + name) + " data-monaco-file=" + contexted_object.fullpath + "/" + name + " data-monaco-ext='DIR' data-monaco-path=" + contexted_object.fullpath + " data-monaco-fullpath=" + contexted_object.fullpath + "/" + name + "><span class='bi bi-folder'><span class='visually-hidden'>&nbsp;</span></span> <span class='monaco_editor_files_name'>" + name + "</span></a></li><li style='display: none;'><ul class='list-unstyled'></ul></li>";
                                add_node(html);
                                $("a[data-monaco-id='" + sha1(contexted_object.fullpath + '/' + name) + "']").bind("contextmenu", contextmenu).click(function () {
                                    $(this).parent().next().toggle();
                                });
                                $(" a[data-monaco-id='" + contexted_object.id + "']").parent().unbind().click(function () {
                                    $(this).next("li").toggle();
                                });
                            }
                        }, "json");
            }
        });
        $("#monaco_files .monaco_files_rename").click(function () {
            let new_name = null;
            new_name = prompt("Renomer :", basename(contexted_object.ext != "DIR" ? contexted_object.file : contexted_object.fullpath));
            if (new_name != null) {
                $.get("./services/index.php?service=s_monaco_editor&action=rename&file=" + btoa(contexted_object.fullpath) + "&name=" + btoa(new_name), function (data) {
                    (data.error ? alertify.error(data.msg) : alertify.success(data.msg));
                    if (!data.error) {
                        $("a[data-monaco-id='" + contexted_object.id + "'] .monaco_editor_files_name").text(new_name);
                        if (contexted_object.ext != "DIR") {
                            $("a[data-monaco-id='" + contexted_object.id + "']").attr("data-monaco-file", new_name);
                        }
                        $("a[data-monaco-id='" + contexted_object.id + "']").attr("data-monaco-fullpath", strtr(contexted_object.fullpath, basename(contexted_object.fullpath), new_name));
                        $("a[data-monaco-id='" + contexted_object.id + "']").attr("data-monaco-id", sha1(contexted_object.fullpath), new_name);
                    }
                }, "json");
            }
        });
        $("#monaco_files .monaco_files_delete").click(function () {
            if (confirm("Êtes vous sur de vouloir supprimer " + contexted_object.fullpath)) {
                $.get("./services/index.php?service=s_monaco_editor&action=delete&file=" + btoa(contexted_object.fullpath), function (data) {
                    (data.error ? alertify.error(data.msg) : alertify.success(data.msg));
                    if (!data.error) {
                        $("a[data-monaco-id='" + contexted_object.id + "']").parent("li").next().empty();
                        $("a[data-monaco-id='" + contexted_object.id + "']").parent("li").next().remove();
                        $("a[data-monaco-id='" + contexted_object.id + "']").parent("li").remove();
                    }
                }, "json");
            }
        });
        $("html").click(function () {
            $(".monaco_files_contextmenu").hide();
        });
    });
});