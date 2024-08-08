<script setup>
    import { useEditor, EditorContent } from '@tiptap/vue-3';
    import StarterKit from '@tiptap/starter-kit';

    import FormatBoldIcon from 'vue-material-design-icons/FormatBold.vue';
    import FormatItalicIcon from 'vue-material-design-icons/FormatItalic.vue';
    import FormatBulletIcon from 'vue-material-design-icons/FormatListBulletedSquare.vue';
    import FormatQuoteIcon from 'vue-material-design-icons/FormatQuoteOpen.vue';
    import LineIcon from 'vue-material-design-icons/Minus.vue';
    import UndoIcon from 'vue-material-design-icons/ArrowULeftTop.vue';
    import RedoIcon from 'vue-material-design-icons/ArrowURightTop.vue';

    const props = defineProps({
        modelValue: {
            required: true,
            type: String
        },
        placeholder: {
            required: false,
            type: String
        }
    });

    const emit = defineEmits(['update:modelValue']);

    const editor = useEditor({
        content: props.modelValue,
        onUpdate: ({editor}) => {
            emit('update:modelValue', editor.getHTML());
        },
        extensions: [
            StarterKit
        ],
        editorProps: {
            attributes: {
                class: 'border border-gray-300 bg-white p-4 min-h-[12rem] outline-indigo-500 rounded-md prose lg:prose-xl max-w-none',
            }
        },
    });
</script>

<template>
    <div class="mt-1">
        <section class="buttons bg-white flex items-center text-gray-700 flex-wrap gap-x-4 border-t border-l border-r border-gray-300 p-2" v-if="editor">
            <button @click="editor.chain().focus().toggleBold().run()"
                    :disabled="!editor.can().chain().focus().toggleBold().run()"
                    :class="{ 'bg-indigo-500 text-gray-200 rounded transition-colors ease-out': editor.isActive('bold') }"
                    class="p-1"
                    type="button">
                <FormatBoldIcon title="Bold" />
            </button>

            <button @click="editor.chain().focus().toggleItalic().run()"
                    :disabled="!editor.can().chain().focus().toggleItalic().run()"
                    :class="{ 'bg-indigo-500 text-gray-200 rounded transition-colors ease-out': editor.isActive('italic') }"
                    class="p-1"
                    type="button">
                <FormatItalicIcon title="Italic" />
            </button>

            <button @click="editor.chain().focus().toggleBulletList().run()"
                    :class="{ 'bg-indigo-500 text-gray-200 rounded transition-colors ease-out': editor.isActive('bulletList') }"
                    class="p-1"
                    type="button">
                <FormatBulletIcon title="Bullet List" />
            </button>

            <button @click="editor.chain().focus().toggleBlockquote().run()"
                    :class="{ 'bg-indigo-500 text-gray-200 rounded transition-colors ease-out': editor.isActive('blockquote') }"
                    class="p-1"
                    type="button">
                <FormatQuoteIcon title="Block Quote" />
            </button>

            <button @click="editor.chain().focus().setHorizontalRule().run()"
                    type="button">
                <LineIcon title="Horizontal Rule" />
            </button>

            <button @click="editor.chain().focus().undo().run()"
                    :disabled="!editor.can().chain().focus().undo().run()"
                    type="button">
                <UndoIcon title="Undo" />
            </button>

            <button @click="editor.chain().focus().redo().run()"
                    :disabled="!editor.can().chain().focus().redo().run()"
                    type="button">
                <RedoIcon title="Redo" />
            </button>
        </section>
        <EditorContent :editor="editor" />
    </div>
</template>

