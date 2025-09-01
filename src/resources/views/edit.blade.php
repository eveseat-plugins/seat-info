@extends('web::layouts.grids.12')

@section('title', trans('info::info.module_title'))
@section('page_header', trans('info::info.module_title'))

@push('head')
    <style>
        .monospace-font {
            font-family: monospace;
        }
        .p-0-75{
            padding: 0.75rem;
        }
        .ml-0-75 {
            margin-left: 0.75rem;
        }
        .btn-delete:hover {
            color: black;
        }
    </style>
@endpush

@section('full')

    <!-- Instructions -->
    <div class="row w-100">
        <div class="col">
            <div class="card-column">

                <div class="card">
                    <div class="card-header">Editor</div>
                    <div class="card-body">

                        <form method="post" action="{{ route('info.save') }}" id="articleform" class="acl-editor-submit-form">
                            @csrf

                            <input type="hidden" name="id" value="{{ $article->id }}">
                            <input type="hidden" name="text" id="submitText" value="{{ $article->text }}">

                            <div class="form-group">
                                <label for="name">{{ trans('info::info.article_name') }}</label>
                                <input type="text" name="name" class="form-control" id="name"
                                       placeholder="{{ trans('info::info.article_name') }}" required
                                       value="{{ $article->name }}">
                            </div>

                            <div class="d-flex w-100 form-group flex-column align-items-start">
                                <div>
                                    <label for="text" id="editorVisibilityScrollTarget">{{ trans('info::info.article_content') }}</label>
                                </div>

                                <div class="btn-group mb-1 btn-group-sm">

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown"><i
                                                    class="fas fa-heading"></i></button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" id="button-insert-heading-1">H1</button>
                                            <button type="button" class="dropdown-item" id="button-insert-heading-2">H2</button>
                                            <button type="button" class="dropdown-item" id="button-insert-heading-3">H3</button>
                                            <button type="button" class="dropdown-item" id="button-insert-heading-4">H4</button>
                                            <button type="button" class="dropdown-item" id="button-insert-heading-5">H5</button>
                                            <button type="button" class="dropdown-item" id="button-insert-heading-6">H6</button>
                                        </div>
                                    </div>

                                    <button type="button" class="btn btn-secondary" id="button-insert-paragraph"><i
                                                class="fas fa-paragraph"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-bold"><i
                                                class="fas fa-bold"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-italic"><i
                                                class="fas fa-italic"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-link"><i
                                                class="fas fa-link"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-image"><i
                                                class="far fa-image"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-list"><i
                                                class="fas fa-list"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-list-ol"><i
                                                class="fas fa-list-ol"></i></button>
                                    <button type="button" class="btn btn-secondary" id="button-insert-color"><i
                                                class="fas fa-palette"></i></button>

                                    <div class="btn-group">
                                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" id="icon-search-initiate"><i
                                                    class="fas fa-satellite"></i></button>
                                        <div class="dropdown-menu">
                                            <input type="text" class="dropdown-item" placeholder="Search item..." id="icon-search">
                                            <div id="icon-search-results">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <input type="radio" name="color-selector" checked id="color-primary-radio">
                                    <input type="color" id="color-primary" value="#CB444A">
                                    <input type="radio" name="color-selector" id="color-secondary-radio">
                                    <input type="color" id="color-secondary" value="#53A551">
                                </div>

                                <div class="d-flex w-100" style="height: 60vh;">
                                    <div class="w-50">
                                         <div class="w-100 h-100 form-control text-sm" id="text"></div>
                                    </div>
                                    <div class="w-50 ml-1">
                                        <div class="form-control h-100 overflow-auto pt-3" id="editor-preview-target">

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <p>{{ trans('info::info.editor_syntax_documentation_link') }} <a
                                            href="https://github.com/recursivetree/seat-info/blob/main/documentation.md"
                                            target="_blank">{{ trans('info::info.link') }}</a></p>
                            </div>

                            <div class="form-group" id="editor-preview-status">
                                <ul id="editor-preview-warnings" class="list-group">
                                </ul>
                            </div>

                            @include("info::components.acl-editor",["roles"=>$roles,"acl_roles"=>$acl_roles])

                            @can("info.make_public")
                                <div class="form-check form-group">
                                    @if($article->public)
                                        <input type="checkbox" class="form-check-input" id="public" name="public" checked>
                                    @else
                                        <input type="checkbox" class="form-check-input" id="public" name="public">
                                    @endif
                                    <label class="form-check-label"
                                           for="public">{{ trans('info::info.editor_public_checkbox') }}</label>
                                </div>
                            @endcan

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary"
                                        id="save">{{ trans('info::info.editor_save_button') }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@push('javascript')
    <script src="@infoVersionedAsset('info/js/lib/ace.js')"></script>

    <script src="@infoVersionedAsset('info/js/parser.js')"></script>
    <script src="@infoVersionedAsset('info/js/render_article.js')"></script>
    <script src="@infoVersionedAsset('info/js/markup_tags.js')"></script>

    <script src="@infoVersionedAsset('info/js/lib/shipfit.min.js')"></script>
    <script>

        $("#view_role_select").select2()
        $("#edit_role_select").select2()

        //ask before you close the tab
        function beforeUnload(event) {
            event.preventDefault();
            event.returnValue = "You have unsaved changes in your article, do you really want to leave?";
        }
        const form = document.getElementById("articleform")
        form.addEventListener("submit",()=>{
            window.removeEventListener("beforeunload",beforeUnload)
        })

        // color picker system
        const colorTextBtn = document.getElementById("button-insert-color")
        const primaryColorPicker = document.getElementById("color-primary")
        const secondaryColorPicker = document.getElementById("color-secondary")
        const primaryColorRadio = document.getElementById("color-primary-radio")
        const secondaryColorRadio = document.getElementById("color-secondary-radio")
        function syncColor() {
            colorTextBtn.style.color = getActiveColor()
        }
        function getActiveColor() {
            if(primaryColorRadio.checked){
                return primaryColorPicker.value;
            } else {
                return secondaryColorPicker.value;
            }
        }
        primaryColorPicker.addEventListener("click",function (){
            primaryColorRadio.checked = true
            syncColor()
        })
        secondaryColorPicker.addEventListener("click",function (){
            secondaryColorRadio.checked = true
            syncColor()
        })
        primaryColorPicker.addEventListener("change",syncColor)
        secondaryColorPicker.addEventListener("change",syncColor)
        primaryColorRadio.addEventListener("change",syncColor)
        secondaryColorRadio.addEventListener("change",syncColor)
        syncColor()


        class MarkupEditor {
            editor;
            astTree;

            constructor(options) {
                this.astTree = null

                this.editor = ace.edit("text");
                this.editor.setTheme("ace/theme/xcode");
                this.editor.session.setMode("ace/mode/xml");
                this.editor.session.setUseWrapMode(true)

                this.editor.setValue(document.getElementById("submitText").value)
                this.editor.clearSelection()
                this.editor.focus()

                this.editor.on("change",  (e) => {
                    window.addEventListener("beforeunload",beforeUnload)
                    this.render_preview()
                })

                this.render_preview()
            }

            selectArea(start, end) {
                document.getElementById("editorVisibilityScrollTarget").scrollIntoView()
                this.editor.selection.setRange(new ace.Range(start.lineIndex, start.colIndex, end.lineIndex, end.colIndex+1), true)
                this.editor.scrollToLine(start.lineIndex)
                this.editor.focus()
            }

            selectAreaFromTokenList(tokens) {
                const selectionRange = Token.getRange(tokens)

                this.selectArea(selectionRange.start, selectionRange.end)
            }

            update_errors(e) {
                if(e.renderData){
                    this.astTree = e.renderData.ast
                }

                let status_container = document.getElementById("editor-preview-status")
                if (e.error || e.warnings.length > 0) {
                    status_container.style.display = "block"
                } else {
                    status_container.style.display = "none"
                }

                let warnings_list = document.getElementById("editor-preview-warnings")
                warnings_list.textContent = "" // clear all entries

                if (e.error) {
                    console.error(e)

                    let liElement = document.createElement("li")
                    liElement.textContent = `{!! trans('info::info.editor_preview_error') !!} ${e.error.message}`
                    liElement.classList.add("list-group-item-danger")
                    liElement.classList.add("list-group-item")
                    warnings_list.appendChild(liElement)
                }

                for (const warning of e.warnings) {
                    const warningTokenRange = Token.getRange(warning.tokens)

                    let liElement = document.createElement("li")
                    liElement.textContent = `L${warningTokenRange.start.lineIndex+1}:${warningTokenRange.start.colIndex}-L${warningTokenRange.end.lineIndex+1}:${warningTokenRange.end.colIndex+1}: ${warning.message}`

                    liElement.addEventListener("click", () => {
                        this.selectArea(warningTokenRange.start,warningTokenRange.end)
                    })

                    liElement.classList.add("list-group-item-warning")
                    liElement.classList.add("list-group-item-action")
                    liElement.classList.add("list-group-item")
                    warnings_list.appendChild(liElement)
                }
            }

            render_preview() {
                const renderTarget = document.getElementById("editor-preview-target")

                document.getElementById("submitText").value = this.editor.getValue()

                const lines = this.editor.session.getLines(0,this.editor.session.getLength())

                const scrollPos = renderTarget.scrollTop

                let preview_target = document.getElementById("editor-preview-target")
                preview_target.textContent = ""// lazy thing to clear the dom

                if (lines.length === 0) {
                    preview_target.classList.add("text-muted")
                    preview_target.textContent = {!! json_encode(trans('info::info.editor_preview_empty_article')) !!}
                } else {
                    preview_target.classList.remove("text-muted")
                }

                render_article(
                    lines,
                    renderTarget,
                    this.update_errors.bind(this),
                    (elementAstRepresentation) => {
                        this.selectAreaFromTokenList(elementAstRepresentation.tokens)
                    })

                renderTarget.scrollTop = scrollPos
            }

            getElementForPosition(aceRange){
                const start = {
                    lineIndex: aceRange.start.row,
                    colIndex: aceRange.start.column
                }
                const end = {
                    lineIndex: aceRange.end.row,
                    colIndex: aceRange.end.column
                }

                function traverseTree(node) {
                    let element = null

                    if(node.isIn(start, end)){
                        element = node
                    }

                    if (node instanceof ASTTag) {
                        for (const contentNode of node.content) {
                            const childElement = traverseTree(contentNode)
                            if (childElement) {
                                element = childElement
                            }
                        }
                    }

                    return element
                }

                if(this.astTree) {
                    return traverseTree(this.astTree.rootNode)
                }

                return null
            }

            removeElement(element){
                let text = element.content
                    .flatMap((e)=>e.tokens)
                    .sort((a,b)=>{
                    if(a.start.lineIndex === b.start.lineIndex && a.start.colIndex === b.start.colIndex){
                        return 0
                    } else if(a.start.lineIndex > b.start.lineIndex){
                        return 1
                    } else  if(a.start.lineIndex === b.start.lineIndex){
                        if(a.start.colIndex > b.start.colIndex) {
                            return 1
                        } else {
                            return -1
                        }
                    } else {
                        return -1
                    }
                })
                .reduce((prev,current)=>prev+current.src,"")

                const range = new ace.Range(element.range.start.lineIndex,element.range.start.colIndex,element.range.end.lineIndex,element.range.end.colIndex+1)
                this.editor.session.replace(range,text)

                this.selectArea(element.range.start,{
                    lineIndex: element.range.start.lineIndex,
                    colIndex: element.range.start.colIndex + text.length - 1
                })
            }

            updateSelection(tagType,selectionStart, selectionEnd, replace, position_after_center_section=false) {
                if (tagType) {
                    const element = getSelectedElementWithType(tagType)
                    if (element) {
                        editor.removeElement(element)
                        this.editor.focus()
                        return
                    }
                }

                const text = (selectionStart || "") + (replace || this.editor.getSelectedText()) + (selectionEnd || "")
                this.editor.session.replace(this.editor.getSelectionRange(), text)

                if (position_after_center_section) {
                    let length = (selectionEnd || "").length
                    this.editor.navigateLeft(length)
                }
                this.editor.focus()
            }
        }

        function getSelectedElementWithType(type) {
            const aceRange = editor.editor.getSelectionRange()
            const element = editor.getElementForPosition(aceRange)

            if(element){
                if (
                    element instanceof ASTTag
                    && element.tagName === type
                ) return element

                else if (
                    element instanceof ASTText
                    && element.parent instanceof ASTTag
                    && element.parent.tagName === type
                ) return element.parent
            }

            return null
        }

        const editor = new MarkupEditor()

        document.getElementById("button-insert-heading-1").addEventListener("click", function () {
            editor.updateSelection("h1","<h1>", "</h1>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-heading-2").addEventListener("click", function () {
            editor.updateSelection("h2","<h2>", "</h2>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-heading-3").addEventListener("click", function () {
            editor.updateSelection("h3","<h3>", "</h3>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-heading-4").addEventListener("click", function () {
            editor.updateSelection("h4","<h4>", "</h4>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-heading-5").addEventListener("click", function () {
            editor.updateSelection("h5","<h5>", "</h5>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-heading-6").addEventListener("click", function () {
            editor.updateSelection("h6","<h6>", "</h6>", null, position_after_center_section=true)
        })


        document.getElementById("button-insert-paragraph").addEventListener("click", function () {
            editor.updateSelection("p","<p>", "</p>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-bold").addEventListener("click", function () {
            editor.updateSelection("b","<b>", "</b>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-italic").addEventListener("click", function () {
            editor.updateSelection("i","<i>", "</i>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-link").addEventListener("click", function () {
            editor.updateSelection("a","<a href=\"seatinfo:article/\">", "</a>", null, position_after_center_section=true)
        })
        document.getElementById("button-insert-image").addEventListener("click", function () {
            editor.updateSelection(null,null, null, "<img src=\"seatinfo:resource/\" alt=\"description of the image\" />")
        })
        document.getElementById("button-insert-list").addEventListener("click", function () {
            editor.updateSelection(null,null, null, "<ul>\n    <li>\n    </li>\n    <li>\n    </li>\n    <li>\n    </li>\n</ul>")
        })
        document.getElementById("button-insert-list-ol").addEventListener("click", function () {
            editor.updateSelection(null,null, null, "<ol>\n    <li>\n    </li>\n    <li>\n    </li>\n    <li>\n    </li>\n</ol>")
        })
        document.getElementById("button-insert-color").addEventListener("click", function () {
            editor.updateSelection("color",`<color color="${getActiveColor()}">`, "</color>", null, position_after_center_section=true)
        })

        let iconSearchAbort = new AbortController()
        document.getElementById("icon-search-initiate").addEventListener("click",()=>{
            // the input is still hidden when activating the button, so focus does nothing.
            // I couldn't find an event that triggers after it is visible, so we hack-fix it with some delay
            setTimeout(()=>document.getElementById("icon-search").focus(),10)
        })
        document.getElementById("icon-search").addEventListener("input",async function (e){
            iconSearchAbort.abort()
            iconSearchAbort = new AbortController()

            const searchTerm = e.target.value
            try {
                const req = await fetch(`{{route("seatcore::fastlookup.items")}}?q=${encodeURIComponent(searchTerm)}`, {
                    signal: iconSearchAbort.signal
                })
                const response = await req.json()

                response.results.sort((a, b) => {
                    return a.text.length - b.text.length
                })

                const btnTarget = document.getElementById("icon-search-results")
                btnTarget.textContent = "" // clear contents
                for (let i = 0; i < Math.min(10, response.results.length); i++) {
                    const item = response.results[i];
                    const btn = document.createElement("button")
                    btn.classList.add("dropdown-item")
                    btn.classList.add("px-1")
                    btn.type = "button"

                    const img = document.createElement("img")
                    img.src=`https://images.evetech.net/types/${item.id}/icon?size=32`
                    img.width = 24
                    img.classList.add("mr-1")
                    btn.appendChild(img)

                    btn.append(item.text)

                    btnTarget.appendChild(btn)
                    btn.addEventListener("click", function () {
                        editor.updateSelection(null, null, null, `<icon src="eve:type-icon/${item.id}/${item.text}" alt="${item.text}" />`)
                        // reset search
                        document.getElementById("icon-search").value = ""
                        btnTarget.textContent = ""
                    })
                }
            } catch (e) {
                if(!(e instanceof DOMException && e.name === "AbortError")) throw e;
            }
        })
    </script>
@endpush