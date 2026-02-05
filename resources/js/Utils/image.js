export const getStorageImage = (path, name = 'Image', w = 200, h = 200) => {
    if (path) {
        return `/storage/${path}`;
    }
    return "/images/placeholder.jpg";
};
