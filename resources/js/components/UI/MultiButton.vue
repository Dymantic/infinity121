<template>
    <span @click="open = !open"
          tabindex="0"
          class="inline-flex justify-between items-center relative outline-none cursor-default">
        <span>{{ text }}</span>
        <span class="border-l ml-2 pl-2 items-center">
            <svg :class="{'inverted': open}"
                 xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 24 24"
                 width="16"
                 height="16"><path class="heroicon-ui"
                                   d="M15.3 9.3a1 1 0 0 1 1.4 1.4l-4 4a1 1 0 0 1-1.4 0l-4-4a1 1 0 0 1 1.4-1.4l3.3 3.29 3.3-3.3z"/></svg>
        </span>
        <div class="multi-button-panel flex flex-col shadow bg-gray-100 text-right"
             :class="{'show': open}">
            <slot></slot>
        </div>
    </span>
</template>

<script type="text/babel">
    export default {
        props: ['text'],

        mounted() {
          window.addEventListener('keyup', ({key}) => {
              if(key === 'Escape') {
                  this.open = false;
              }
          });
        },

        data() {
            return {
                open: false,
            };
        }
    }
</script>

<style scoped
       lang="less"
       type="text/less">
    svg path {
        fill: currentColor;
    }

    svg.inverted {
        transform: scale(1, -1);
    }

    .multi-button-panel {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        transition: .2s;
        transform: rotateX(90deg);
        transform-origin: top center;
        padding: .5rem .25rem;
        font-weight: 400;
    }

    .multi-button-panel.show {
        transform: rotateX(0deg);
    }

    .multi-button-panel > * {
        margin: .5rem 0;
        cursor: default;
    }

</style>