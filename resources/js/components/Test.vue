<template>
  <div>
    <h1>Test component</h1>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Test Compo");
    let test = [{ id: 1 }, { id: 2 }];
    let t = test.filter((el) => el.id != 1);

    // console.log(t, "he");
    // console.log(test);
  },
};
</script>
