const boxarea = document.querySelector(".box-area");
const image1Btn = document.querySelector("input[name='image1']");
const image2Btn = document.querySelector("input[name='image2']");
const image3Btn = document.querySelector("input[name='image3']");
const customBtn = document.querySelector("#custom-btn");
const image1 = document.querySelector("img[name='image1']");
const image2 = document.querySelector("img[name='image2']");
const image3 = document.querySelector("img[name='image3']");

function image1Active()
{
    image1Btn.click();
    showImageWhenSelected(image1Btn, image1);
}

function image2Active()
{
    image2Btn.click();
    showImageWhenSelected(image2Btn, image2);
}

function image3Active()
{
    image3Btn.click();
    showImageWhenSelected(image3Btn, image3);
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
