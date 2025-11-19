export const formatDate = (dateString: string | undefined = '') => {
    const date = new Date(dateString);
    return `${date.getDate()} - ${date.getMonth() + 1} - ${date.getFullYear()}`;
};
