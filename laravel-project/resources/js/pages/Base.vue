<script setup lang="ts">
    import { ref } from 'vue';
    import { useForm } from "@inertiajs/vue3";

    type Prompt = {id: number, text: string}
    type PromptText = Record<string, string>;

    let idPrompts = 0;
    let idPromptVersion = 1000;

    const showModalAdd = ref(false);
    const showModalPromptEdit = ref(false);
    const showModalPromptVersionEdit = ref(false);
    const showPromptVersion = ref(false);

    const newPrompt = ref('');
    const promptSelected = ref('');
    const editorText = ref('');
    const promptVersionSelected = ref('');

    const prompts = ref<Prompt[]>([]);
    const promptsVersion = ref<Record<string, Prompt[]>>({});
    const promptText = ref<PromptText>({});

    const form = useForm({
        name: '',
    })

    function addPrompt() {
        prompts.value.push({ id: idPrompts++, text: newPrompt.value });

        promptsVersion.value[newPrompt.value] = [];
        promptsVersion.value[newPrompt.value].push({id: idPromptVersion++, text: newPrompt.value + ".v0"});
        
        newPrompt.value = '';
        
        form.post('/prompts', {
            onSuccess: () => {
                form.reset()
                showModalAdd.value = false
            },
        })
    }

    const promptEditName = ref<Prompt>({id: 0, text: ''});

    function editPrompt(){
        promptsVersion.value[newPrompt.value] = promptsVersion.value[promptEditName.value.text];
        promptEditName.value.text = newPrompt.value;
        showModalPromptEdit.value = false;
    }

    function removePrompt(prompt: Prompt) {
        prompts.value = prompts.value.filter((t) => t !== prompt);
        promptsVersion.value[prompt.text] = [];
        showModalPromptEdit.value = false;
        promptVersionSelected.value = '';
    }

    function removePromptVersion(prompt: Prompt) {
        promptsVersion.value[promptSelected.value] = promptsVersion.value[promptSelected.value].filter((t) => t.id !== prompt.id);
        showModalPromptVersionEdit.value = false;
        promptVersionSelected.value = '';
    }

    function promptVersionsSelected(prompt: string) {
        promptSelected.value = prompt;
        showPromptVersion.value = true;
    }

    function savePrompt(){
        const nextVersion = promptsVersion.value[promptSelected.value].length
        const nameNewPrompt = `${promptSelected.value}.v${(nextVersion)}`
        
        promptsVersion.value[promptSelected.value].push({id: idPromptVersion++, text: nameNewPrompt});
        
        promptText.value[nameNewPrompt] = editorText.value;
    }
</script>

<template>
    <div class="w-full container">
        <div class="mx-10 py-5 flex justify-between">
            <h1 class="text-4xl">Prompts</h1>

            <button class="bg-black text-white px-4 py-2 rounded-xl text-xl" @click="showModalAdd = true">+ Add</button>
        </div>
    
    </div>

    <div v-if="showModalAdd" @click.self="showModalAdd = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">Novo prompt</h2>

            <form class="mt-3 space-y-3" @submit.prevent="addPrompt">
                <input v-model="form.name" required placeholder="Nome do prompt" class="w-full rounded-md border px-3 py-2" type="text">
                
                <div class="flex justify-end gap-2">
                    <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalAdd = false">Cancelar</button>
                    <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div v-if="showModalPromptEdit" @click.self="showModalPromptEdit = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">{{promptEditName.text}}</h2>

            <form @submit.prevent="editPrompt" class="mt-3 space-y-3">
                <input type="text" v-model="newPrompt" required placeholder="Mudar nome" class="w-full rounded-md border px-3 py-2">
                
                <div class="flex justify-between">
                    <button @click="removePrompt(promptEditName)" type="button" class="text-lg rounded-md bg-red-500 text-white px-4 py-2">Deletar</button>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalPromptEdit = false">Cancelar</button>
                        <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div v-if="showModalPromptVersionEdit" @click.self="showModalPromptEdit = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">{{promptEditName.text}}</h2>

            <form @submit.prevent="editPrompt" class="mt-3 space-y-3">
                <input type="text" v-model="newPrompt" required placeholder="Mudar nome" class="w-full rounded-md border px-3 py-2">
                
                <div class="flex justify-between">
                    <button @click="removePromptVersion(promptEditName)" type="button" class="text-lg rounded-md bg-red-500 text-white px-4 py-2">Deletar</button>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalPromptEdit = false">Cancelar</button>
                        <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full container">
        <div class="mx-10 flex space-x-5">
            <ul class="space-y-3">
                <li v-for="prompt in prompts" :key="prompt.id" class="flex border rounded-lg py-2 px-4 text-lg w-70">
                    <button @click="promptVersionsSelected(prompt.text), promptVersionSelected = ''" class="w-full text-start">{{ prompt.text }}</button>
                    <button @click="promptEditName = prompt, showModalPromptEdit = true" class="w-7"> &#8942; </button>
                </li>
            </ul>

            <ul v-if="showPromptVersion" class="space-y-3">
                <li v-for="promptVersion in promptsVersion[promptSelected]" :key="promptVersion.id" class="flex border rounded-lg py-2 px-4 text-lg w-70">
                    <button @click="promptVersionSelected = promptVersion.text, editorText = promptText[promptVersionSelected]" class="w-full text-start">{{ promptVersion.text }}</button>
                    <button @click="promptEditName = promptVersion, showModalPromptVersionEdit = true" class="w-7"> &#8942; </button>
                </li>
            </ul>

            <div v-if="promptVersionSelected" class="w-full fle flex-col">
                <h2 class="text-2xl">{{ promptVersionSelected }}</h2>
                <textarea v-model="editorText" class="border rounded-2xl w-full h-190"></textarea>

                <div class="flex justify-end space-x-8 p-5">
                    <button type="button" @click="editorText = ''" class="text-lg rounded-md px-4 py-2 border">Limpar</button>
                    <button @click="savePrompt" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</template>