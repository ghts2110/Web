<script setup>
    import { ref } from 'vue';

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

    const prompts = ref([]);
    const promptsVersion = ref({});
    const promptText = ref({"1.v0": "oi", "2.v0": "ola"});

    function addPrompt() {
        prompts.value.push({ id: idPrompts++, text: newPrompt.value });

        promptsVersion.value[newPrompt.value] = [];
        promptsVersion.value[newPrompt.value].push({id: idPromptVersion++, text: newPrompt.value + ".v0"});
        
        newPrompt.value = '';
        
        showModalAdd.value = false;
    }

    const promptEditName = ref('');
    function showEditPrompt(promtp) {
        promptEditName.value = promtp;
    }   

    function editPrompt(){
        promptsVersion.value[newPrompt.value] = promptsVersion.value[promptEditName.value.text];
        promptEditName.value.text = newPrompt.value;
        showModalPromptEdit.value = false;
    }

    function removePrompt(prompt) {
        prompts.value = prompts.value.filter((t) => t !== prompt);
        promptsVersion.value[prompt.text] = [];
        showModalPromptEdit.value = false;
    }

    function removePromptVersion(prompt) {
        promptsVersion.value[promptSelected.value] = promptsVersion.value[promptSelected.value].filter((t) => t.id !== prompt.id);
        showModalPromptVersionEdit.value = false;
    }

    function promptVersionsSelected(prompt){
        promptSelected.value = prompt;
        showPromptVersion.value = true;
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
                <input class="w-full rounded-md border px-3 py-2" type="text" v-model="newPrompt" required placeholder="Nome do prompt">
                
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
            <h2 class="text-2xl">{{promptEditName.text}} .-.</h2>

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
                    <button @click="promptVersionsSelected(prompt.text)" class="w-full text-start">{{ prompt.text }}</button>
                    <button @click="showEditPrompt(prompt), showModalPromptEdit = true" class="w-7"> &#8942 </button>
                </li>
            </ul>

            <ul v-if="showPromptVersion" class="space-y-3">
                <li v-for="promptVersion in promptsVersion[promptSelected]" :key="promptVersion.id" class="flex border rounded-lg py-2 px-4 text-lg w-70">
                    <button @click="promptVersionSelected = promptVersion.text, editorText = promptText[promptVersionSelected]" class="w-full text-start">{{ promptVersion.text }}</button>
                    <button @click="showEditPrompt(promptVersion), showModalPromptVersionEdit = true" class="w-7"> &#8942 </button>
                </li>
            </ul>

            <div class="w-full fle flex-col">
                <h2 class="text-2xl">{{ promptVersionSelected }}</h2>
                <textarea v-model="editorText" class="border rounded-2xl w-full h-190"></textarea>

                <div class="flex justify-end space-x-8 p-5">
                    <button type="button" @click="editorText = ''" class="text-lg rounded-md px-4 py-2 border">Limpar</button>
                    <button class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                </div>
            </div>
        </div>
    </div>
</template>