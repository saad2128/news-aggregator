const customStyles = {
    control: (provided, state) => {
        return {
            ...provided,
            "&:hover": {
                border: state.isFocused ? "1px solid #2563eb" : "1px solid rgb(209 213 219/1)",
            },
            background: "#fff",
            borderColor: "rgb(209 213 219/1)",
            boxShadow: state.isFocused ? null : null,
            padding: "0.35rem 1rem", // Adjust the padding value here
        };
    },

    valueContainer: (provided, state) => ({
        ...provided,
        padding: "0 6px",
    }),

    input: (provided, state) => ({
        ...provided,
        margin: "0px",
        "input:focus": {
            boxShadow: "none",
        },
    }),
    indicatorSeparator: (state) => ({
        display: "none",
    }),
    indicatorsContainer: (provided, state) => ({
        ...provided,
        height: "30px",
    }),
};

export default customStyles;
