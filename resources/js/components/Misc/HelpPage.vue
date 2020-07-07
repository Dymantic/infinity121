<template>
    <page>
        <page-header title="How to do things"></page-header>
        <help-video
            title="1. Getting started with your profile and account"
            description="How to manage your account (change password, etc), and how to set up your profile so that Infinity can make your profile public and start assigning you lessons."
            video="lBHJlGSsQzo"
        ></help-video>

        <help-video
            title="2. Setting your teaching times"
            description="For Infinity to assign you lessons, you need to tell us when you are available, and not available."
            video="fVWD2WAqSmU"
        ></help-video>

        <help-video
            title="3. Setting your working locations"
            description="You can't be everywhere. This is how you let Infinity know where you are available to work."
            video="qNCYG1671Vk"
        ></help-video>

        <help-video
            title="4. Logging lessons"
            description="You need to log your lessons for Infinity to know they are done. This is what you need to do, and how you can do it."
            video="-IQKS7SdvWk"
        ></help-video>
    </page>
</template>

<script type="text/babel">
import Page from "../UI/Page";
import PageHeader from "../UI/PageHeader";
import HelpVideo from "./HelpVideo";

export default {
    components: {
        Page,
        PageHeader,
        HelpVideo
    }
};
</script>
