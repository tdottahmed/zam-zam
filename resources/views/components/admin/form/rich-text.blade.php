@props(['name', 'label' => '', 'value' => '', 'height' => 'h-48'])

<div class="w-full" x-data="{ 
    content: {{ json_encode($value) }},
    init() {
        if (typeof Quill === 'undefined') {
            console.error('Quill JS is not loaded.');
            return;
        }

        let quill = new Quill(this.$refs.editor, {
            theme: 'snow',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'header': 1 }, { 'header': 2 }],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'color': [] }, { 'background': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            },
            placeholder: 'Write something amazing...',
        });

        // Set initial content
        if (this.content) {
            quill.root.innerHTML = this.content;
        }

        // Update x-model on change
        quill.on('text-change', () => {
             this.content = quill.root.innerHTML;
        });
    }
}">
    @if($label)
        <label class="block font-medium text-sm text-gray-700 mb-1">{{ $label }}</label>
    @endif

    <div class="bg-white">
        <div x-ref="editor" class="h-64 rounded-b-lg"></div>
    </div>
    
    <input type="hidden" name="{{ $name }}" x-model="content">

    <!-- Stylings for Quill to match Tailwind -->
    <style>
        .ql-toolbar.ql-snow {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border-color: #d1d5db; /* gray-300 */
        }
        .ql-container.ql-snow {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border-color: #d1d5db; /* gray-300 */
        }
        .ql-editor {
            font-family: inherit;
            font-size: 0.875rem; /* text-sm */
        }
    </style>
</div>
