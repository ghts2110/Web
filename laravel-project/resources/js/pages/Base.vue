<script setup>
    import { ref } from 'vue';

    let idPrompts = 0;
    let idPromptVersion = 1000;

    const showModalAdd = ref(false);
    const showModalEdit = ref(false);
    const showPromptVersion = ref(false);

    const newPrompt = ref('');
    const prompts = ref([]);
    const promptVersion = ref({});
    const promptSelected = ref('');

    function addPrompt() {
        prompts.value.push({ id: idPrompts++, text: newPrompt.value });

        promptVersion.value[newPrompt.value] = [];
        promptVersion.value[newPrompt.value].push({id: idPromptVersion++, text: newPrompt.value + ".v0"});
        
        newPrompt.value = '';
        
        showModalAdd.value = false;
    }

    const promptEditName = ref('');
    function showEditPrompt(promtp) {
        promptEditName.value = promtp;
        showModalEdit.value = true;
    }   

    function editPrompt(){
        promptVersion.value[newPrompt.value] = promptVersion.value[promptEditName.value.text];
        promptEditName.value.text = newPrompt.value;
        showModalEdit.value = false;
    }

    function removePrompt(prompt) {
        prompts.value = prompts.value.filter((t) => t !== prompt);
        promptVersion.value[prompt.text] = [];
        showModalEdit.value = false;
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

    <div v-if="showModalEdit" @click.self="showModalEdit = false" class="fixed inset-0 bg-black/40 flex items-center justify-center p-4">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">{{promptEditName.text}}</h2>

            <form @submit.prevent="editPrompt" class="mt-3 space-y-3">
                <input type="text" v-model="newPrompt" required placeholder="Mudar nome" class="w-full rounded-md border px-3 py-2">
                
                <div class="flex justify-between">
                    <button @click="removePrompt(promptEditName)" type="button" class="text-lg rounded-md bg-red-500 text-white px-4 py-2">Deletar</button>
                    <div class="flex justify-end gap-2">
                        <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModalEdit = false">Cancelar</button>
                        <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full container">
        <div class="mx-10 flex space-x-5">
            <ul class="space-y-3">
                <li v-for="prompt in prompts" :key="prompt.id" class="flex justify-between border rounded-lg py-2 px-4 text-lg space-x-60">
                    <button @click="promptVersionsSelected(prompt.text)" class="w-full text-start">{{ prompt.text }}</button>
                    <button @click="showEditPrompt(prompt)" class=""> &#8942 </button>
                </li>
            </ul>

            <ul v-if="showPromptVersion" class="space-y-3">
                <li v-for="promptVertion in promptVersion[promptSelected]" :key="promptVertion.id" class="flex justify-between border rounded-lg py-2 px-4 text-lg space-x-60">
                    <button class="w-full text-start">{{ promptVertion.text }}</button>
                    <button @click="showEditPrompt(promptVertion)" class=""> &#8942 </button>
                </li>
            </ul>

            <div class="border rounded-2xl w-full h-200">

            </div>
        </div>
    </div>
</template>