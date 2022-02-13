const boxarea = document.querySelector(".box-area");
const imageBtn = document.querySelector("input[name='image']");
const customBtn = document.querySelector("#custom-btn");
const image = document.querySelector("img[name='image']");

function imageActive()
{
    imageBtn.click();
    showImageWhenSelected(imageBtn, image);
}

function showImageWhenSelected(button, image)
{
    button.addEventListener("change", function() {
        const file = this.files[0];
        if (file)
        {
            const reader = new FileReader();
            reader.onload = function() {
                const result = reader.result;
                image.src = result;
                boxarea.classList.add("active");
            }
            reader.readAsDataURL(file);
        }
    });
}
