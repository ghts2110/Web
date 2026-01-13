<script setup>
    import { ref } from 'vue';

    let idPrompts = 0;
    let idPromptVersion = 1000;

    const showModal = ref(false);
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
        
        showModal.value = false;
    }

    function removePrompt(prompt) {
        prompts.value = prompts.value.filter((t) => t !== prompt);
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

            <button class="bg-black text-white px-4 py-2 rounded-xl text-xl" @click="showModal = true">+ Add</button>
        </div>
    
    </div>


    <div class="fixed inset-0 bg-black/40 flex items-center justify-center p-4" v-if="showModal" @click.self="showModal = false">
        <div class="w-full max-w-md rounded-lg bg-white p-4 shadow">
            <h2 class="text-2xl">Novo prompt</h2>

            <form class="mt-3 space-y-3" @submit.prevent="addPrompt">
                <input class="w-full rounded-md border px-3 py-2" type="text" v-model="newPrompt" required placeholder="Nome do prompt">
                
                <div class="flex justify-end gap-2">
                    <button type="button" class="text-lg rounded-md px-4 py-2 border" @click.self="showModal = false">Cancelar</button>
                    <button type="submit" class="text-lg rounded-md bg-black text-white px-4 py-2">Salvar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="w-full container">
        <div class="mx-10 flex">
            <ul class="">
                <li v-for="prompt in prompts" :key="prompt.id" class="flex justify-between border rounded-lg py-2 px-4 text-lg space-x-70">
                    <button @click="promptVersionsSelected(prompt.text)" class="w-full text-start">{{ prompt.text }}</button>
                    <button @click="removePrompt(prompt)" class="text-red-500"> remover </button>
                </li>
            </ul>

            <ul v-if="showPromptVersion" class="">
                <li v-for="promptVertion in promptVersion[promptSelected]" :key="promptVertion.id" class="flex justify-between border rounded-lg py-2 px-4 text-lg space-x-70">
                    <button class="w-full text-start">{{ promptVertion.text }}</button>
                    <button @click="removePrompt(promptVertion)" class="text-red-500"> remover </button>
                </li>
            </ul>

            
        </div>
    </div>
</template>